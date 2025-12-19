<button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
    Get your transfercode here
</button>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Offcanvas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div>
            Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
            <!-- here is the transfercode code -->
            <div class="transferContainer">
                <form action="" method="POST" id="getTransferCode">
                    <div class="transfercode">
                        <label for="user" class="form-label">Username</label>
                        <input class="form-control" type="text" name="user" id="user" placeholder="your name" required>
                        <small class="form-text">Please enter your first name.</small>
                    </div>

                    <div class="transfercode">
                        <label for="api_key" class="form-label">API Key</label>
                        <input class="form-control" type="text" name="api_key" id="api_key" required>
                        <small class="form-text">Please enter your API Key.</small>
                    </div>

                    <div class="transfercode">
                        <label for="amount" class="form-label">Amount</label>
                        <input class="form-control" type="number" name="amount" id="amount" required>
                        <small class="form-text">Please confirm the amount you wish to withdraw.</small>
                    </div>

                    <button type="submit">Get transferCode</button>
                </form>
            </div>
            <!-- here does the transfer code code stops -->
        </div>
    </div>
</div>
</div>