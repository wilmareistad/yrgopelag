<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\BookingException;
use App\Exceptions\PaymentException;
use App\Http\CentralBankClient;
use App\Repositories\BookingRepository;
use App\Repositories\FeatureRepository;
use App\Repositories\GuestRepository;
use App\Repositories\RoomRepository;

final class BookingService
{
    public function __construct(
        private readonly RoomRepository $rooms,
        private readonly FeatureRepository $features,
        private readonly GuestRepository $guests,
        private readonly BookingRepository $bookings,
        private readonly AvailabilityService $availability,
        private readonly PricingService $pricing,
        private readonly CentralBankClient $centralBank,
    ) {
    }

    public function book(BookingRequest $request): BookingResult
    {
        $room = $this->rooms->find($request->roomId);

        if ($room === null) {
            throw new BookingException('Selected room does not exist.');
        }

        if (!$this->availability->isAvailable($request->roomId, $request->checkIn, $request->checkOut)) {
            throw new BookingException('Room is already booked for the selected dates.');
        }

        $nights = $this->pricing->nights($request->checkIn, $request->checkOut);
        $roomTotal = $this->pricing->roomTotal($room, $nights);

        $allFeatures = $this->features->all();
        $featuresTotal = $this->pricing->featuresTotal($allFeatures, $request->features);
        $totalPrice = $roomTotal + $featuresTotal;

        // Payment must clear before anything is persisted.
        $this->centralBank->validateTransferCode($request->transferCode, $totalPrice);
        $this->centralBank->deposit($request->transferCode);

        $guest = $this->guests->firstOrCreateByName($request->name);
        $bookingId = $this->bookings->create(
            $guest->id,
            $room->id,
            $request->checkIn,
            $request->checkOut,
            $totalPrice,
        );

        $byName = [];
        foreach ($allFeatures as $feature) {
            $byName[$feature->feature] = $feature;
        }

        $featuresUsed = [];
        foreach ($request->features as $featureName) {
            $feature = $byName[$featureName] ?? null;

            if ($feature === null) {
                continue;
            }

            $this->bookings->attachFeature($bookingId, $feature->id);

            $featuresUsed[] = [
                'activity' => $feature->activity,
                'tier' => $feature->priceLevel !== null ? strtolower($feature->priceLevel) : null,
            ];
        }

        $receiptWarning = null;

        try {
            $this->centralBank->sendReceipt(
                $request->name,
                $request->checkIn->format('Y-m-d'),
                $request->checkOut->format('Y-m-d'),
                $featuresUsed,
            );
        } catch (PaymentException) {
            $receiptWarning = 'Booking saved but receipt could not be sent.';
        }

        return new BookingResult(
            guestName: $request->name,
            roomId: $room->id,
            checkIn: $request->checkIn,
            checkOut: $request->checkOut,
            nights: $nights,
            roomTotal: $roomTotal,
            totalPrice: $totalPrice,
            receiptWarning: $receiptWarning,
        );
    }
}
