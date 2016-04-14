<div class="auth">
    <?php $this->load->helper('url'); ?>
    <script src="<?php echo base_url(); ?>noCI_lib/datepicker/tcal.js" type="text/javascript"></script>
    <h2><?php echo $title; ?></h2>
</div>
<div class="dform">
<?php echo form_open('booking/booking_add_job'); ?>
    <input type="hidden" name="huid" value="<?php echo $huid; ?>" />
    <table class="tform">
      <tbody>

        <tr>
            <td class="label">Дата заезда</td>
            <td class="input"><input type="text" name="datein" class="tcal icalInput" value="" /></td>

            <td class="label">Дата выезда</td>
            <td class="input"><input type="text" name="dateout" class="tcal icalInput" value="" /></td>
        </tr>

        <tr>
            <td class="label">Контактное лицо (гость)</td>
            <td class="input" colspan="3"><textarea name="person" maxlength="1024" rows="5" cols="80"></textarea></td>
        </tr>

        <tr>
            <td class="label">Телефон</td>
            <td class="input" colspan="3"><input type="text" name="personphone" value="" size="50"/></td>
        </tr>

        <tr>
            <td class="label">Общая сумма проживания</td>
            <td class="input" colspan="3"><input type="text" name="totalsum" value="" size="20"/></td>
        </tr>

        <tr>
            <td class="label">Сумма предоплаты</td>
            <td class="input"><input type="text" name="beforepaysum" value="" size="20"/></td>

            <td class="label">Дата внесения предоплаты</td>
            <td class="input"><input type="text" name="beforepaydate" class="tcal icalInput" value="" /></td>
        </tr>

        <tr>
            <td class="label">Комментарии</td>
            <td class="input" colspan="3"><textarea name="comments" maxlength="4096" rows="5" cols="80"></textarea></td>
        </tr>

        <tr>
            <td colspan="4" class="button"><input type="submit" name="submit" value="Добавить" /></td>
        </tr>
      </tbody>
    </table>
</form>
</div>
</div>