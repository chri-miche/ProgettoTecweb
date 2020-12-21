<?php

    //TODO: In page building we have multple possible components this means that
    // it's hard to unserstand the h1, h2 etc stuff. Find a way to make a component H1?.
    // (most simple solution is to keep for each important component H1 in the code, maybe a subclass
    // maincontent? To consider.

    /*** IDEA: Make h1 h2 h3 h4 h5 h6 as header and make component an abstract class in which
            every istance has a H element. ?*/
    interface Component {

        function build();

    }

?>