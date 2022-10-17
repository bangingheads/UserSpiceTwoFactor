<?php

global $userdetails;
?>


<div class="form-group">
    <label class="switch switch-text switch-success">
    <input id="twofa" name="twofa" type="checkbox" class="switch-input" <?php if ($userdetails->twofaforced==1) {echo 'checked="true"';} ?>>
    <span data-on="Yes" data-off="No" class="switch-label"></span>
    <span class="switch-handle"></span>
    </label>
    <label for="twofa">&nbsp;Force Two Factor Authentication</label>
    <?php $status = $userdetails->twoEnabled ? "Enabled" : "Disabled"; ?>
    <p>Current 2FA Status: <?php echo $status; ?></p>
</div>