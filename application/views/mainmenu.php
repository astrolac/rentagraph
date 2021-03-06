<div class="emptycontainer">
<div class="mainmenu">
    <ul id="mainmenu">

        <?php
        function echoarr ($inarr) {
            echo "<ul>";
            foreach ($inarr as $menuitem) {
                echo "<li><a href=\"".$menuitem['href']."\">".$menuitem['title']."</a>";
                if (is_array($menuitem['subm'])) {
                    echoarr($menuitem['subm']);
                }
                echo "</li>";
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
        foreach ($mainmenuarray as $menuitem) {
            echo "<li><a href=\"".$menuitem['href']."\">".$menuitem['title']."</a>";
            if (is_array($menuitem['subm']) && count($menuitem['subm']) > 0) {
                echoarr($menuitem['subm']);
            }
            echo "</li>";
        }
        ?>

    </ul>
</div>
</div>
