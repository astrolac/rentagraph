<div class="auth">
    <h2><?php echo $title; ?></h2>
</div>
<div class="dform">
<?php echo form_open('booking/booking_cancel_job'); ?>
    <input type="hidden" name="isshown" value="<?php
        if(isset($buid)) {
            echo 'YES';
        } else {
            echo 'NO';
        }
    ?>" />
    <table class="tform">
      <tbody>

        <tr>
            <td class="label">Введите номер брони</td>
            <td class="input">
                <input  type="text"
                        name="buid"
                        size="50"
                        value="<?php
                            if(isset($buid)) {
                                echo $buid;
                            }
                        ?>" />
            </td>
        </tr>

        <tr>
            <td colspan="2" class="button"><input type="submit" name="submit" value="Снять бронь" /></td>
        </tr>
      </tbody>
    </table>
</form>
</div>
</div>
