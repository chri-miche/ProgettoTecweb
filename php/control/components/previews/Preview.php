<?php


    interface Preview extends Component{

        /** Every preview has to build itself?
         * @param int $id */
        public function __construct(int $id);

    }