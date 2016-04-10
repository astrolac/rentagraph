<div class="mainmenu">
    <ul id="mainmenu">

        <?php
        function echoarr ($inarr) {
            echo "<ul>";
            foreach ($inarr as $key => $value) {
                if (is_array($value)) {
                    echo "<li>".$menuname;
                    echoarr($value);
                    echo "</li>";
                } else {
                    echo "<li><a href=\"".$value."\">".$key."</a></li>";
                }
            }
            echo "</ul>";
        }
        ?>

        <?php
        foreach ($mainmenuarray as $menuname => $menuhref) {
            if (is_array($menuhref)) {
                echo "<li>".$menuname;
                echoarr($menuhref);
                echo "</li>";
            } else {
                echo "<li><a href=\"".$menuhref."\">".$menuname."</a></li>";
            }
        }
        ?>

    </ul>
</div>
