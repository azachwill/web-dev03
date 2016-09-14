<?php
    require_once 'JaduConstants.php';
    header('Content-type: text/xml');
?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
    <ShortName>Fordham.edu</ShortName>
    <Description>Fordham.edu</Description>
    <Image height="16" width="16" type="image/x-icon">http://<?php print DOMAIN; ?>/favicon.ico</Image>
    <Url type="text/html" method="get" template="http://www.google.com?q={searchTerms}" />
</OpenSearchDescription>
