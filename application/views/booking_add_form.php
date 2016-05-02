<div class="auth">
    <?php $this->load->helper('url'); ?>
    <script src="<?php echo base_url(); ?>noCI_lib/datepicker/tcal.js" type="text/javascript"></script>
    <h2><?php echo $title; ?></h2>
</div>
<!-- <div>
  <pre>
    <?php /*
      if(isset($isedit) && $isedit == "YES") {
        foreach($forminfo as $key => $value) {
            echo "Key: ".$key." Value: ".$value."\n";
        }
      }
    */ ?>
  </pre>
</div> -->
<div class="dform">
<?php echo form_open('booking/booking_add_job'); ?>
    <input type="hidden" name="huid" value="<?php
        if(isset($isedit) && $isedit == "YES") {
            echo $forminfo['huid'];
        } else {
            echo $huid;
        }
    ?>" />

    <input type="hidden" name="buid" value="<?php
        if(isset($isedit) && $isedit == "YES") {
            echo $forminfo['uid'];
        } else {
            echo "FALSE";
        }
    ?>" />

    <input type="hidden" name="isedit" value="<?php
        if(isset($isedit) && $isedit == "YES") {
            echo "YES";
        } else {
            echo "NO";
        }
    ?>" />

    <table class="tform">
      <tbody>

        <tr>
            <td class="label">Дата заезда</td>
            <td class="input"><input  type="text"
                                      name="datein"
                                      class="tcal icalInput"
                                      value="<?php if(isset($forminfo)) { echo $forminfo['datein']; } ?>" />
            </td>

            <td class="label">Дата выезда</td>
            <td class="input">
                <input  type="text"
                        name="dateout"
                        class="tcal icalInput"
                        value="<?php if(isset($forminfo)) { echo $forminfo['dateout']; } ?>" />
            </td>
        </tr>

        <tr>
            <td class="label">Контактное лицо (гость)</td>
            <td class="input" colspan="3">
                <textarea name="person" maxlength="1024" rows="5" cols="80"><?php if(isset($forminfo)) { echo $forminfo['person']; } ?></textarea>
            </td>
        </tr>

        <tr>
            <td class="label">Телефон</td>
            <td class="input" colspan="3">
                <input  type="text"
                        name="personphone"
                        size="50"
                        value="<?php if(isset($forminfo)) { echo $forminfo['personphone']; } ?>" />
            </td>
        </tr>

        <tr>
            <td class="label">Общая сумма проживания</td>
            <td class="input" colspan="3">
                <input  type="text"
                        name="totalsum"
                        size="20"
                        value="<?php if(isset($forminfo)) { echo $forminfo['totalsum']; } ?>" />
            </td>
        </tr>

        <tr>
            <td class="label">Сумма предоплаты</td>
            <td class="input">
                <input  type="text"
                        name="beforepaysum"
                        size="20"
                        value="<?php if(isset($forminfo)) { echo $forminfo['beforepaysum']; } ?>" />
            </td>

            <td class="label">Дата внесения предоплаты</td>
            <td class="input">
                <input  type="text"
                        name="beforepaydate"
                        class="tcal icalInput"
                        value="<?php if(isset($forminfo)) { echo $forminfo['beforepaydate']; } ?>" />
            </td>
        </tr>

        <tr>
            <td class="label">Комментарии</td>
            <td class="input" colspan="3">
                <textarea name="comments" maxlength="4096" rows="5" cols="80"><?php if(isset($forminfo)) { echo $forminfo['comments']; } ?></textarea>
            </td>
        </tr>

        <tr>
            <td class="label" colspan="4">Бронь владельца
              <input  type="checkbox"
                      name="byowner"
                      <?php if(isset($forminfo)) { if($forminfo['byowner'] == 'on') {echo "checked";} } ?> />
            </td>
        </tr>

        <tr>
            <td colspan="4" class="button"><input type="submit" name="submit" value="<?php
                if(isset($isedit) && $isedit == "YES") {
                    echo "Изменить";
                } else {
                    echo "Добавить";
                }
            ?>" /></td>
        </tr>
      </tbody>
    </table>
</form>
</div>
</div>
