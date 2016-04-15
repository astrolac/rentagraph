<div id="addinfo">
  <table class="hotels">
      <thead>
        <tr>
          <?php $header = array_shift($addinfo);
              foreach ($header as $value) { ?>
                <td><?php echo $value; ?></td>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
          <?php foreach ($addinfo as $row) { ?>
          <tr>
              <?php foreach ($row as $value) { ?>
                  <td><?php echo $value; ?></td>
              <?php } ?>
          </tr>
          <?php } ?>
      </tbody>
  </table>
</div>
