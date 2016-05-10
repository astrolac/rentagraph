<div class="auth">
    <h2><?php echo $title; ?></h2>

    <?php /*echo validation_errors();*/ ?>

    <div class="dform">
    <?php echo form_open('base/hotelsadd_job'); ?>

        <input type="hidden" name="isedit" value="<?php
            if(isset($hoteldata)) {
                echo "YES";
            } else {
                echo "NO";
            }
        ?>" />

        <input type="hidden" name="huid" value="<?php
            if(isset($hoteldata)) {
                echo $hoteldata['uid'];
            }
        ?>" />

        <table class="tform">
          <tbody>

            <tr>
                <td class="label">Наименование отеля</td>
                <td class="input"><input type="text" name="hname" value="<?php
                    if(isset($hoteldata)) {
                        echo $hoteldata['hname'];
                    }
                ?>" size="80" /></td>
            </tr>

            <tr>
                <td class="label">Тип</td>
                <td class="input">
                    <select name="htype" size="1">
                        <?php foreach ($htypes as $value) { ?>
                        <option value="<?php echo $value; ?>"<?php
                            if(isset($hoteldata) && $hoteldata['htype'] == $value) {
                                echo " selected";
                            }
                        ?>><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
            </tr>

            <tr>
                <td class="label">Родительский отель</td>
                <td class="input">
                    <select name="puid" size="1">
                        <option value="0">Отсутствует</option>
                        <?php foreach ($hnames as $key => $value) { ?>
                        <option value="<?php echo $key; ?>"<?php
                            if(isset($hoteldata) && $hoteldata['puid'] == $key) {
                                echo " selected";
                            }
                        ?>><?php echo $value['hname']; ?></option>
                        <?php } ?>
                    </select>
            </tr>

            <tr>
                <td class="label">Контактное лицо (хозяин)</td>
                <td class="input"><textarea name="person" maxlength="1024" rows="5" cols="80"><?php
                    if(isset($hoteldata)) {
                        echo $hoteldata['person'];
                    }
                ?></textarea></td>
            </tr>

            <tr>
                <td class="label">Телефон</td>
                <td class="input"><input type="text" name="personphone" value="<?php
                    if(isset($hoteldata)) {
                        echo $hoteldata['personphone'];
                    }
                ?>" size="50"/></td>
            </tr>

            <tr>
                <td class="label">Адрес</td>
                <td class="input"><textarea name="address" maxlength="255" rows="5" cols="80"><?php
                    if(isset($hoteldata)) {
                        echo $hoteldata['haddress'];
                    }
                ?></textarea></td>
            </tr>

            <tr>
                <td class="label">Комментарии</td>
                <td class="input"><textarea name="comments" maxlength="4096" rows="5" cols="80"><?php
                    if(isset($hoteldata)) {
                        echo $hoteldata['hcomments'];
                    }
                ?></textarea></td>
            </tr>

            <tr>
                <td class="label">Комиссия в %</td>
                <td class="input"><input type="text" name="percentfee" value="<?php
                    if(isset($hoteldata)) {
                        echo $hoteldata['percentfee'];
                    }
                ?>" size="50"/></td>
            </tr>

            <tr>
                <td class="label">Фиксированная комиссия</td>
                <td class="input"><input type="text" name="fixedfee" value="<?php
                    if(isset($hoteldata)) {
                        echo $hoteldata['fixedfee'];
                    }
                ?>" size="50"/></td>
            </tr>

            <tr>
                <td colspan="2" class="button"><input type="submit" name="submit" value="<?php
                    if(isset($hoteldata)) {
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
