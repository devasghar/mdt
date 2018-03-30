<!-- jQuery & jQuery UI-->
<script
    src="https://code.jquery.com/jquery-2.2.4.min.js"
    integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
    crossorigin="anonymous">
</script>

<script
        src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
        integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
        crossorigin="anonymous">
</script>

<!-- Configurations -->
<script>
    $(document).ready(function(){
        /*
         * Toggle active & arrow on nested elements
         */
        $("dl.mdt dt").click(function(){
            $(this).nextUntil("dl.mdt dt", "dl.mdt dd").toggleClass("active", 'fast','easeOutCirc');
            $("dl.mdt dd").has("dl dd dt").addClass("arrow-down", 300);
        });

        /*
         * Add round bottom corners on last elements
         */
        $('.mdt').each(function(){
            $('.mdt dd:last-child').addClass("rnd-btm");
            $('.mdt dd:last-child').has("dl dt").addClass("rnd-btm");
        });

        /*
         * Selecting parents
         */
        var parentsElem = new Array();
        $('dl.mdt dd :not .arrow-down').click(function(){
            console.log("working");
            //parentsElem.push($(this).prevUntil("dl.mdt", "dt")[0].innerHTML.toString());
        });
        //console.log(parentsElem);
    });
</script>

<!-- Stylings -->
<style>
    dl.mdt dl{
        width: 100%;
        top: 0;
        left: 0;
        height: .8em;
        position: relative;
        display: initial;
        z-index: 3;
    }

    dl.mdt dt{
        padding: .2em .5em .2em .5em;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        z-index: 2;
    }

    dl.mdt dd{
        padding: .1em .25em .1em 1.25em;
        left: -20px;
        position: absolute;
        display: none;
        opacity: 0;
        z-index: 1;

    }

    dl.mdt .active{
        position: relative;
        display: block !important;
        height:auto;
        opacity: 1;
    }

    dl.mdt .arrow-down:before{
        content: "\2937";
        position: absolute;
        left: .1em;
        top: 0.35em;
    }

    dl.mdt .rnd-btm{
        padding-bottom: 1em;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
    }

    dl.mdt .align-right{
        text-align: right;
    }

    dl.mdt .align-left{
        text-align: left;
    }

    dl.mdt span.align-right{
        position: absolute;
        right: 0;
    }

    /*
    Theme settings
    */
    dl.mdt dt{
        background-color: #f55555;
        color: #fff;
        border: 1px solid #333;
    }

    dl.mdt dd{
        background-color: #333;
        color: #fff;
    }

    dl.mdt .arrow-down:before{
        color: #fff;
    }
</style>


<?php

function mdt($array = array()) {
    print "<dl class='mdt'><dt>mdt</dt><dd class='align-right'>";
    $bt = debug_backtrace();
    $caller = array_shift($bt);
    print "Called from file {$caller['file']} & line {$caller['line']}";
    print mdt_encap($array);
    print "</dd></dl>";

}

function mdt_encap($array = array(), $out = "", $keys = array()) {
    if(is_array($array)) {
        $out .= "<dl class='align-left'>";
        foreach($array as $key => $val) {
            if(is_array($val)) {
                $out .= "<dt>{$key}</dt><dd>";
                $keys[] = $key;
                $out .= mdt_encap($val, "", $keys);
                $out .= "</dd>";
            } else {
                $keys[] = $key;
                $var = mdt_extract_var($keys);
                if (($index = array_search($key, $keys)) !== false && $keys[$index] === $val) {
                    unset($keys[$index]);
                }
                $out .= "<dt>{$key}</dt><dd>{$val}<span class='align-right'>{$var}</span></dd>";
            }
            if (($index = array_search($key, $keys)) !== false && !is_array($keys[$index])) {
                unset($keys[$index]);
            }
        }
        $out .= "</dl>";
    } else {
        $out .= "<pre class='align-left'>";
        $out .= strip_tags(htmlspecialchars($array), '<head><style><script>');
        $out .= "</pre>";
    }

    return $out;
}

function mdt_extract_var($array = array()) {
    $out = "\$var";
    foreach($array as $key => $value) {
        $out .= "[\"{$value}\"]";
    }
    return $out;
}

?>