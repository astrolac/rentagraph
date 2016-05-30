<div class="innermenu">
    <?php
    foreach ($innermenu as $key => $value) {
        echo "<a href=\"".$value."\"><button class=\"innermenubutton\">".$key."</button></a> ";
    }
    ?>
</div>
<div class="dform">
  <?php echo form_open('authoz/scope_edit_job'); ?>

  <input type="hidden" name="scopeid" value="<?php echo $scopeid; ?>" />

  <table class="tform">
    <tbody>
      <tr>
        <td colspan="2" class="button"><input type="submit" name="submit" value="Внести изменения" /></td>
      </tr>

      <tr><th colspan="2"><u>Отели в области видимости</u></th></tr>
      <?php
      foreach ($inscope as $row) { ?>
        <tr>
          <td class="label"><?php
              if($row['isactive'] == 1) {
                  echo $row['hname'];
              } else {
                  echo "<del>".$row['hname']."</del>";
              }
          ?></td>
          <td class="input"><input type="checkbox" name="<?php echo $row['uid']; ?>" checked /></td>
        </tr>
      <?php }  ?>
      <tr>
        <td colspan="2" class="button"><input type="submit" name="submit" value="Внести изменения" /></td>
      </tr>
    </tbody>
  </table>
  <br />
  <table class="tform">
    <tbody>
      <tr><th colspan="2"><u>Отели вне области видимости</u></th></tr>
      <?php
      foreach ($notinscope as $row) { ?>
        <tr>
          <td class="label"><?php
              if($row['isactive'] == 1) {
                  echo $row['hname'];
              } else {
                  echo "<del>".$row['hname']."</del>";
              }
          ?></td>
          <td class="input"><input type="checkbox" name="<?php echo $row['uid']; ?>" /></td>
        </tr>
      <?php }  ?>
      <tr>
        <td colspan="2" class="button"><input type="submit" name="submit" value="Внести изменения" /></td>
      </tr>
    </tbody>
  </table>

  </form>
</div>
