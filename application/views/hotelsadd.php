<div class="auth">
    <h2><?php echo $title; ?></h2>

    <?php /*echo validation_errors();*/ ?>

    <div class="dform">
    <?php echo form_open('base/hotelsadd_job'); ?>

        <table class="tform">
          <tbody>

            <tr>
                <td class="label">Наименование отеля</td>
                <td class="input"><input type="text" name="hname" value="" size="80" /></td>
            </tr>

            <tr>
                <td class="label">Тип</td>
                <td class="input">
                    <select name="htype" size="1">
                        <?php foreach ($htypes as $value) { ?>
                        <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
            </tr>

            <tr>
                <td class="label">Контактное лицо (хозяин)</td>
                <td class="input"><textarea name="person" maxlength="1024" rows="5" cols="80"></textarea></td>
            </tr>

            <tr>
                <td class="label">Телефон</td>
                <td class="input"><input type="text" name="personphone" value="" size="50"/></td>
            </tr>

            <tr>
                <td class="label">Адрес</td>
                <td class="input"><textarea name="address" maxlength="255" rows="5" cols="80"></textarea></td>
            </tr>

            <tr>
                <td class="label">Комментарии</td>
                <td class="input"><textarea name="comments" maxlength="4096" rows="5" cols="80"></textarea></td>
            </tr>

            <tr>
                <td class="label">Комиссия в %</td>
                <td class="input"><input type="text" name="percentfee" value="" size="50"/></td>
            </tr>

            <tr>
                <td class="label">Фиксированная комиссия</td>
                <td class="input"><input type="text" name="fixedfee" value="" size="50"/></td>
            </tr>

            <tr>
                <td class="label">Цена</td>
                <td class="input"><input type="text" name="price" value="" size="50"/></td>
            </tr>

            <tr>
                <td colspan="2" class="button"><input type="submit" name="submit" value="Добавить" /></td>
            </tr>
          </tbody>
        </table>
    </form>
    </div>
</div>
