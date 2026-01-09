# Yrgopelag

A school project built during the first year of the 'Webbutveckling' program at Yrgo.

Yrgopelag/Scubaland is a hotel booking website where users can view rooms and make bookings, and admin can manage rooms and prices through an admin page.

## Features

### First page

The application first page displays available rooms, features and the booking form using data fetched from the database.

### User

View available rooms
See room types and prices: budget, standard, luxury
Book a room via form
Receive a booking receipt

### Admin

Admin login
Update room and feature prices

## Built with

PHP
HTML
CSS
JavaScript
SQLite
Composer
Guzzle

## Author

**Wilma Reistad**
Web Development student at Yrgo

## Code Review
By: Elsa Girardo

index.php 44: Change "natt" to "night" for consistency since the rest of the page is in English.

index.php 79: hotelContainer could maybe be renamed to bookingDatesContainer or something similar for more clarity.

index.php 114-115: I think it would be beneficial to add a read only Total section here that calculates the total for the selected room, dates, and features so that the user doesn't have to do the math themselves while booking.

index.php 125: misspelling in class name, should be tran<ins>s</ins>fercodeForm.

book.php/book.js: I think the file could be renamed to transferCode.php/transferCode.js or something similar for clarity.

book.php/book.js: Add error handling for user error. I tested entering my name and a wrong API-key to see what would happen and nothing happens on screen, but in the console there is an error message displayed. Could be helpful for users if they see that an error has occured.

book.php 27-31: If you implement the suggestion for ``` index.php 114-115 ``` above, it could also be good to link the amount input with the total from the index page so that is automatically generated for the user and minimizes the risk for user error.

assets/scripts/booking.js: empty file, could be removed for cleaner repo.

assets/styles/index.css 93-97: I would consider moving the button a little further down the page closer to the bottom right corner for a more uniformed look. It sits a little high and feels slightly out of place. 

views/header.php 26-31 AND views/footer.php 5-8: I would remove the nav section entirely since the website is contained within the one page and the Start button links to the page you're already on. In the footer you could replace it with a scroll to the top button perhaps.











