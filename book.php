<form action="" method="POST" class="getTransfercode">
    <div class="transfercode">
        <label for="user" class="form-label">Username</label>
        <input class="form-control" type="text" name="user" id="user" placeholder="your name" required>
        <small class="form-text">Please provide your first name.</small>
    </div>

    <div class="transfercode">
        <label for="api_key" class="form-label">API Key</label>
        <input class="form-control" type="text" name="api_key" id="api_key" required>
        <small class="form-text">Please provide your API Key.</small>
    </div>

    <div class="transfercode">
        <label for="amount" class="form-label">Amount</label>
        <input class="form-control" type="number" name="amount" id="amount" required>
        <small class="form-text">Please confirm the amount you wish to withdraw.</small>
    </div>

    <button type="submit" class="btn">Get transferCode</button>
</form>