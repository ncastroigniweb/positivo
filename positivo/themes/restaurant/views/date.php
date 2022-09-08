<ul class="nav-system">
    <li class="nav-time">
        <ert-nav-time class="ng-isolate-scope ">
            <div class="nav-time-date">
                <small class="ng-binding ">
                    <script type="text/javascript">
                        var d = new Date();
                        var dia = <?= json_encode(lang('days')); ?>;
                        document.write("<?= lang('today_title') ?>: " + dia[d.getDay()]);</script>
                </small>
                <strong class="ng-binding ">
                    <script type="text/javascript">
                        var hoy = new Date();
                        var m = hoy.getMonth() + 1;
                        var mes = (m < 10) ? '0' + m : m;
                        document.write(+d.getDate());
                    </script>
                    <script type="text/javascript">
                        var mm = new Date();
                        var m2 = mm.getMonth() + 1;
                        var mesok = (m2 < 10) ? '0' + m2 : m2;
                        var mesok = <?= json_encode(lang('months')); ?>;;
                        document.write(" " + mesok[mm.getMonth()]);
                    </script>
                </strong>
            </div>
            <div class="nav-time-time">
                <span class="ng-binding ">
                    <script type="text/javascript">
                        function startTime() {
                            today = new Date();
                            h = today.getHours();
                            m = today.getMinutes();
                            s = today.getSeconds();
                            m = checkTime(m);
                            s = checkTime(s);
                            document.getElementById('reloj').innerHTML = h + ":" + m<!--+":"+s-->;
                            t = setTimeout('startTime()', 500);
                        }
                        function checkTime(i) {
                            if (i < 10) {
                                i = "0" + i;
                            }
                            return i;
                        }
                        window.onload = function () {
                            startTime();
                        }
                    </script>
                    <div id="reloj"></div>
                </span>
            </div>
        </ert-nav-time>
    </li>
</ul>


