<div class="emptycontainer">
<div class="mainmenu">
    <ul id="mainmenu">

        <?php
        function echoarr ($inarr) {
            echo "<ul>";
            foreach ($inarr as $key => $value) {
                if (is_array($value)) {
                    echo "<li><a href=\"#\">".$key."</a>";
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
        /*
            Здесь, запускаем в цикле перебор массива с пунктами меню.
            Функция выше запускает перебор переданного ей массива.
            Для вложенных массивов использует рекурсию.
            Можно было бы обойтись и просто самой функцией, без этого цикла, но
            у главного списка есть id, а у вложенных его нет.
        */
        foreach ($mainmenuarray as $menuname => $menuhref) {
            if (is_array($menuhref)) {
                echo "<li><a href=\"#\">".$menuname."</a>";
                echoarr($menuhref);
                echo "</li>";
            } else {
                echo "<li><a href=\"".$menuhref."\">".$menuname."</a></li>";
            }
        }
        ?>

    </ul>
</div>
</div>
