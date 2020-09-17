<?php
    include_once 'PageBase.php';

    class PageWrapper extends PageBase
    {
        public function load()
        {
            parent::load();
        }

        public function body()
        {
            ?>
            <header class="col">
                <img url="/img/logo">
            </header>
            <nav class="list row nowrap t7">
                <li>
                    <p>BALUSTRADY</p>
                    <ul>
                        <li>ZEWNĘTRZNE</li>
                        <li>WEWNĘTRZNE</li>
                    </ul>
                </li>
                <li>
                    <p>OGRODZENIA</p>
                    <ul>
                        <li>FURTKI</li>
                        <li>BRAMY</li>
                        <li>PRZĘSŁA</li>
                        <li>OGRODZENIA</li>
                    </ul>
                </li>
                <li>KONSTRUKCJE</li>
                <li>
                    <p>DOM</p>
                    <ul>
                        <li>GRILE</li>
                        <li>ŁAWKI</li>
                        <li>STOJAKI</li>
                        <li>KWIETNIKI</li>
                        <li>SCHODY</li>
                    </ul>
                </li>
                <li>
                    <p>SKLEP</p>
                    <ul>
                        <li>ELEMENTY KUTE</li>
                        <li>WYPOSAŻENIE BRAM</li>
                        <li>ZMAKI</li>
                        <li>KLAMKI</li>
                    </ul>
                </li>
                <li>KONTAKT</li>
            </nav>
            <main>
                <?php $this->main();?>
            </main>
            <footer class="row">
                <img src="/img/logo">
                <div>
                    <
                </div>
            </footer>
            <?php
        }

        abstract protected function main();
    }
?>