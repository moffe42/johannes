<?php

// conf
$path         = "/rpc/rpcxml.php";
$host         = "user-agent-string.info";
$port         = "80";
$access_key = "free"; //"free" or Your key

// view source this file and exit
if ($_GET['source'] == "y") {     show_source(__FILE__);     exit; }

// header page
scriptheader();

// form
echo "<form method=\"get\">";
echo "<b>user agent string</b>: <i>(Paste any user agent string in this field and click 'Analyze', maybe select debug output view.)</i><br /><textarea cols=\"100\" rows=\"3\" name=\"uas\">".$_GET['uas']."</textarea><br />";
if ($_GET['debug'] == 1) { $dactive = " selected"; $nactive = "";} else { $nactive = " selected"; $dactive = "";}
echo "Output: <select name=\"debug\">";
echo "<option value=\"0\"".$nactive.">Normal</option>";
echo "<option value=\"1\"".$dactive.">Debug</option>";
echo "</select>&nbsp;<input type=\"submit\" value=\"Analyze\">";
echo "</form>";

if ($_GET['uas'] ) {
    // include client lib from http://phpxmlrpc.sourceforge.net/
    include 'xmlrpc.inc';

    // ua example: Mozilla/5.0 (Windows; U; Windows NT 5.1; cs; rv:1.8.1) Gecko/20061010 Firefox/2.0
    $ua = base64_encode($_GET['uas']);

    // query build
    $message = new xmlrpcmsg('ua.search', array(new xmlrpcval($ua, 'string'),new xmlrpcval($access_key, 'string') )    );
    if ($_GET['debug']) {echo "<pre>\n---REQUEST---\n" . htmlentities($message->serialize()) . "\n---END---\n\n</pre>"; }

    // new xmlrpc_client
    $server = new xmlrpc_client($path,$host,$port);
    if ($_GET['debug']) { $server->setDebug(1); }
    $res = $server->send($message);

    // if error
    if ($res->faultCode()) { echo "<p><b>ERROR</b><br />".$res->faultString();    }

    // print response data
    else {
        $struct = $res->value();
        $flag_val = $struct->structmem('flag');
        $flag = $flag_val->scalarval();
        echo "<b>System flag:</b> ".$flag."<br />";
        // if flag == 5 -> system error
        if ($flag == 5) {
            $errortext_val = $struct->structmem('errortext'); $errortext = $errortext_val->scalarval();
            echo "<b>ERROR text:</b> ".$errortext;
        }
        else {
            $typ_val = $struct->structmem('typ');                         $typ = $typ_val->scalarval();
            $ua_family_val = $struct->structmem('ua_family');             $ua_family = $ua_family_val->scalarval();
            $ua_name_val = $struct->structmem('ua_name');                 $ua_name = $ua_name_val->scalarval();
            $ua_url_val = $struct->structmem('ua_url');                 $ua_url = $ua_url_val->scalarval();
            $ua_company_val = $struct->structmem('ua_company');         $ua_company = $ua_company_val->scalarval();
            $ua_company_url_val = $struct->structmem('ua_company_url'); $ua_company_url = $ua_company_url_val->scalarval();
            $ua_icon_val = $struct->structmem('ua_icon');                 $ua_icon = $ua_icon_val->scalarval();

            $os_family_val = $struct->structmem('os_family');             $os_family = $os_family_val->scalarval();
            $os_name_val = $struct->structmem('os_name');                 $os_name = $os_name_val->scalarval();
            $os_url_val = $struct->structmem('os_url');                 $os_url = $os_url_val->scalarval();
            $os_company_val = $struct->structmem('os_company');         $os_company = $os_company_val->scalarval();
            $os_company_url_val = $struct->structmem('os_company_url'); $os_company_url = $os_company_url_val->scalarval();
            $os_icon_val = $struct->structmem('os_icon');                 $os_icon = $os_icon_val->scalarval();

            echo "<b>Typ:</b> ".$typ."<br />";
            if ($ua_url == "unknown") {$ua_f = $ua_url; } else { $ua_f = "<a href=\"".$ua_url."\">".$ua_family."</a>"; }
            echo "<b>UA family:</b> <img src=\"".$ua_icon."\" width=\"16\" height=\"16\" border=\"0\"> ".$ua_f."<br />";
            echo "<b>UA name:</b> ".$ua_name."<br />";
            if ($ua_company_url == "unknown") {$ua_c = $ua_company; } else { $ua_c = "<a href=\"".$ua_company_url."\">".$ua_company."</a>"; }
            echo "<b>UA company:</b> ".$ua_c."<br />";
            echo "<b>OS family:</b> ".$os_family."<br />";
            if ($os_url == "unknown") {$os_f = $os_name; } else { $os_f = "<a href=\"".$os_url."\">".$os_name."</a>"; }
            echo "<b>OS name:</b> <img src=\"".$os_icon."\" width=\"16\" height=\"16\" border=\"0\"> ".$os_f."<br />";
            if ($os_company_url == "unknown") {$os_c = $os_company; } else { $os_c = "<a href=\"".$os_company_url."\">".$os_company."</a>"; }
            echo "<b>OS company:</b> ".$os_c."<br />";

        }
    }
}

// end page
foot();

// ---- function ----
function  scriptheader() {
    echo "<html>\n";
    echo "<head>\n";
    echo "<title>RPC-XML PHP example :: user-agent-string.info</title>\n";
    echo "</head>\n";
    echo "<body>\n";
    echo "<h1>RPC-XML PHP exapmle</h1>\n<hr />";
}
function  foot() {
    echo "<hr />";
    echo "<a href=\"http://user-agent-string.info/\">user-agent-string.info</a>:
    <b><a href=\"/api\">Api</a> RPC-XML PHP exapmle</b> - <a href=\"?source=y\">source code</a>
    - this script use lib <a href=\"xmlrpc.inc\">xmlrpc.inc</a> from <a href=\"http://phpxmlrpc.sourceforge.net/\">http://phpxmlrpc.sourceforge.net/</a>";
    echo "</body></html>\n";
}
