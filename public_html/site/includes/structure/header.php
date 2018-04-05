<?php
    require_once 'egov/JaduEGovJoinedUpServices.php';
    require_once 'JaduConstants.php';
    include_once('custom/Nav/Menu.php');
?>
<!-- googleoff: index -->
<?php
    $subsiteMenu = \custom\Nav\Menu::findByCodename('left_hand_navigation', array(
            'stopAt' => array(
                'up' => 'subsite'
            ),
            'maxUpDistance' => null,
            'depth' => 2,
            'siblings' => true,
            'showHeading' => true,
            'relativeToReference' => true,
            'referenceParameters' => $_GET,
            'attributes' => array(
                'outer' => array('class' => 'inline-large clearfix'),
                'div' => array('class' => 'inline-large clearfix'))
        ));
    $subsiteMenuItem = $subsiteMenu->rootItemForView;
    $isSubsite = $subsiteMenuItem && ($subsiteMenuItem instanceof \custom\Nav\MenuItem) && $subsiteMenuItem->hasClass('subsite');
?>
<header class="sticky header<?php if ($isSubsite) : ?> header--subsite<?php endif; ?>">
<a class="skip-nav" href="#main-fordham-content" tabindex="1">Skip to main content</a>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/structure/' . (($isSubsite) ? 'header-nav-2' : 'header-nav-1') . '.php';
?>
</header>
<!-- googleon: index -->
