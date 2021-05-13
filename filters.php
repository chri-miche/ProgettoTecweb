<?php

function sanitize_simple_text(string $stringa): string {
    return htmlspecialchars($stringa, ENT_QUOTES);
}

function sanitize_simple_markdown(string $stringa): string {
    // tengo solo i tag b em sse sono ben formati. non si possono combinare
    return preg_replace('#&lt;(em|b)&gt;(.*?)&lt;/\1&gt;#', '<\1>\2</\1>', htmlspecialchars($stringa, ENT_QUOTES));
}

?>