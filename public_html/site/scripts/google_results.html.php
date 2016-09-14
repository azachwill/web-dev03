<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/doctype.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/stylesheets.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/metadata.php';
?>
        <meta name="dcterms.description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>'s search results">
        <meta name="dcterms.subject" content="<?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>">
        <meta name="dcterms.title" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Search results">
        <meta name="description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>'s search results">
        <meta name="keywords" content="<?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>">
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/opening_javascript.php';
?>
        <title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/opening.php';
?>
                <h2>You searched for <strong><?php print $currentRupaSearch->getFullQueryForXHTML(); ?></strong></h2>
<?php
    if (!empty($currentRupaSearchResult->spellingSuggestions)) {
?>
                <h3>Did you mean <?php
        $count = 1;
        foreach ($currentRupaSearchResult->spellingSuggestions as $spelling) {
            $tempRupaSearch = $currentRupaSearch->getClone();
            $tempRupaSearch->clearFullQuery();
            $tempRupaSearch->setQuery($spelling->query);

            print '<a href="' . getSiteRootURL() . '/site/scripts/google_results.php?' . $tempRupaSearch-> getRupaQueryStringForURL() . '">' . encodeHtml($spelling->query) . '</a>';
            if ($count < count($currentRupaSearch->spellingSuggestions)) {
                print ', ';
            }
            $count++;
        }
                ?>?</h3>
<?php
    }
    if ($resultCount < 1) {
?>
                <div class="callout callout__error">
                    <h2>Sorry, no results were found</h2>
                </div>
<?php
    }
    if (!empty($currentRupaSearchResult->resultSynonyms)) {
?>
                <h3>You could also try: <?php
                    $count = 1;
                    foreach ($currentRupaSearchResult->resultSynonyms as $synonym) {
                        $tempRupaSearch = $currentRupaSearch->getClone();
                        $tempRupaSearch->clearFullQuery();
                        $tempRupaSearch->setQuery(urldecode($synonym->query));
                        $synonymQuery = $tempRupaSearch->getRupaQueryStringForURL();

                        print '<a href="' . getSiteRootURL() . '/site/scripts/google_results.php?' . $synonymQuery . '">' . encodeHtml($synonym->suggestion) . '</a>';

                        if ($count < count($currentRupaSearchResult->resultSynonyms)) {
                            print ', ';
                        }

                        $count++;
                    }
                        ?></h3>
<?php
    }
    if ($resultCount > 0) {
?>
                <p>Results <strong><?php print $currentRupaSearchResult->startNumber; ?> &ndash; <?php print $currentRupaSearchResult->endNumber; ?></strong> of about <strong><?php print $resultCount; ?></strong>. Search took <strong><?php print encodeHtml($currentRupaSearchResult->time); ?></strong> seconds.</p>
<?php
    }
    // check for greater than 1 as "all results" should always show up
    if (count($collectionsForRefine) > 1) {
?>
                <div class="callout">
                    <h3>Refine by:</h3>
                    <ul class="item-list">
<?php
        $index = 0;

        foreach ($collectionsForRefine as $collection) {
?>
                        <li>
<?php
            if ($index > 0) {
?>
                            <img alt="" src="<?php print getSiteRootURL() . '/images/' . encodeHtml($collection[0]->imageName); ?>" class="image__icon">
<?php
            }
?>
                            <a href="<?php print getSiteRootURL() . '/site/scripts/google_results.php?' . $collection[1]; ?>"><?php print encodeHtml($collection[0]->friendlyName); ?></a>
                        </li>
<?php
            $index++;
        }
?>
                    </ul>
                </div>
<?php
    }
    if (!empty($currentRupaSearchResult->resultKeyMatches)) {
        foreach ($currentRupaSearchResult->resultKeyMatches as $keymatch) {
?>
                <div class="callout callout__feature">
                    <h3>
                        <a href="<?php print encodeHtml($keymatch->url); ?>"><?php print encodeHtml($keymatch->name); ?></a>
                    </h3>
                    <p><?php print encodeHtml($keymatch->url); ?></p>
                </div>
<?php
        }
    }
    if ($resultCount > 0 && !empty($currentRupaSearchResult->resultItems)) {
        $tempRupaSearch = $currentRupaSearch->getClone();
        if ($currentRupaSearch->sortBy != 'date') {
            $tempRupaSearch->setSortBy('date');
?>
                <p><a href="<?php print getSiteRootURL() . '/site/scripts/google_results.php?' . $tempRupaSearch->getRupaQueryStringForXHTML(); ?>">Sort by date</a> or relevance</p>
<?php
        }
        else {
            $tempRupaSearch->setSortBy('');
?>
                <p>Sort by date or <a href="<?php print getSiteRootURL() . '/site/scripts/google_results.php?' . $tempRupaSearch->getRupaQueryStringForXHTML(); ?>">relevance</a></p>
<?php
        }
?>
                <ul class="list-results">
<?php
        foreach ($currentRupaSearchResult->resultItems as $result) {
            $icon = '';

            switch ($result->mime) {
                case '':
                case 'text/html':
                case 'text/plain':
                    break;
                default:
                    $icon = getIconForMimeType($result->mime);
                    break;
            }

            $href = '';

            if ($result->url != '') {
                $href = $result->url;

                foreach ($allRupaRenameResultURLs as $renameResultURL) {
                    $href = str_replace($renameResultURL->fromPattern, $renameResultURL->replaceWith, $href);
                }
            }

            $title = $result->getTitleForXHTML();
            $snippet = $result->getSnippetForXHTML();

            if ((defined('RUPA_RESULT_TITLE_METADATA_FIELD') && RUPA_RESULT_TITLE_METADATA_FIELD != '') ||
                (defined('RUPA_RESULT_SNIPPET_METADATA_FIELD') && RUPA_RESULT_SNIPPET_METADATA_FIELD != '')) {
                foreach ($result->metadata as $metadata) {
                    if ($metadata->name == RUPA_RESULT_TITLE_METADATA_FIELD && $metadata->value != '') {
                        $title = encodeHtml($metadata->value);
                    }
                    if ($metadata->name == RUPA_RESULT_SNIPPET_METADATA_FIELD && $metadata->value != '') {
                        $snippet = encodeHtml($metadata->value);
                    }
                }
            }
            // replace the download filename with the download title
            if (mb_eregi('/download', $result->url) && trim($title) == '') {
                $filename = str_replace('http://' . DOMAIN . '/', '', $result->url);
                $filename = mb_eregi_replace('downloads/', '', $filename);
                $filename = mb_eregi_replace('file\/([0-9]+)/', '', $filename);
                $filename = mb_eregi_replace('download\/([0-9]+)/', '', $filename);
                $filename = mb_eregi_replace('([0-9]+)/', '', $filename);
                $filename = mb_eregi_replace('_', ' ', $filename);
                $title = ucwords($filename);
            }
            if (trim($title) == '') {
                $title = '[No title]';
            }
            else {
                $title = str_replace(METADATA_GENERIC_NAME . ' - ', '', strip_tags($title));
                $title = str_replace('- ' . METADATA_GENERIC_NAME, '', strip_tags($title));
            }

            $extraInfo = '';

            if ($result->size != '') {
                $extraInfo = ' &ndash; ' . $result->size;
            }
            if ($result->documentDate != '') {
                $extraInfo .= ' &ndash; ' . $result->documentDate;
            }

            //get the collection for this result
            $collections  = getCollectionsForUrl($result->url, true);
            $images = array();

            if (is_array($collections)) {
                foreach($collections as $collection) {
                    $images[$collection->friendlyName] = $collection->imageName;
                }
            }
?>
                    <li>
                        <h3>
                            <a href="<?php print encodeHtml($href); ?>"><?php print $title; ?></a>
<?php
            if ($icon != '') {
?>
                            <img alt="<?php print $result->mime; ?>" src="<?php print getStaticContentRootURL() . '/site/images/file_type_icons/' . $icon; ?>">
<?php
            }
?>
                        </h3>
<?php
            foreach ($images as $name => $image) {
?>
                        <a href="<?php print encodeHtml($collectionImageLinks[$name]); ?>">
                            <img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($image); ?>" alt="" class="image__icon">
                        </a>
<?php
            }
?>
                        <p><?php print $snippet; ?></p>
                        <small><?php print encodeHtml($href.$extraInfo); ?></small>
                    </li>
<?php
        }
?>
                </ul>
<?php
        // if we're filtering, we haven't reached the end and the appliance says
        // there's no next page, show option to turn off filter
        $resultsOmitted = false;

        if ($currentRupaSearch->getFilterResults() != FILTER_OFF && $currentRupaSearchResult->documentsFound > $currentRupaSearchResult->endNumber  && $currentRupaSearchResult->nextPage == false) {
            $resultsOmitted = true;
            $tempRupaSearch = $currentRupaSearch->getClone();
            $tempRupaSearch->setStartNum(0);
            $tempRupaSearch->setFilterResults(FILTER_OFF);
?>
                <p>In order to show you the most relevant results, we have omitted some entries very similar to the <?php print $currentRupaSearchResult->endNumber; ?> already displayed. If you like, you can <a href="<?php print getSiteRootURL().buildSearchResultsURL() . '?' . encodeHtml($tempRupaSearch->getRupaQueryStringForURL()); ?>">repeat the search with the omitted results included</a>.</p>
<?php
        }

        $pageCount = floor($resultCount / PAGE_SEARCH_RESULT_COUNT) + 1;

        // if number of pages is a multiple of ten we don't need the last page
        if (($resultCount % 10) == 0) {
            $pageCount--;
        }
        if ($pageCount > 1) {
?>
                <div class="pagination pagination-full">
<?php
            $currentPage = (($currentRupaSearchResult->startNumber - 1) / 10) + 1;

            if ($currentPage > MAXIMUM_NAV_PAGE_COUNT / 2) {
                $startPage = max(1, intval($currentPage - (MAXIMUM_NAV_PAGE_COUNT / 2)));
                $endPage = min($pageCount, intval($currentPage + (MAXIMUM_NAV_PAGE_COUNT / 2)));
            }
            else {
                $startPage = 1;
                $endPage = $startPage + (MAXIMUM_NAV_PAGE_COUNT - 1);
            }

            $endPage = min($endPage, $pageCount);

            if (!empty($currentRupaSearchResult->resultItems)) {
                if ($currentRupaSearchResult->previousPage || $currentRupaSearchResult->startNumber > 1) {
                    $tempRupaSearch = $currentRupaSearch->getClone();
                    $tempRupaSearch->setStartNum($currentRupaSearchResult->startNumber - (PAGE_SEARCH_RESULT_COUNT + 1));
                    $queryString = $tempRupaSearch->getRupaQueryStringForXHTML();
?>
                    <a href="<?php print getSiteRootURL().'/site/scripts/google_results.php?' . $queryString; ?>" class="button float_left">Previous</a>
<?php
                }
?>
                <ul class="list-pagination">
<?php
                for ($i = $startPage; $i <= $endPage; $i++) {
                    if ($i != $currentPage) {
                        $tempRupaSearch = $currentRupaSearch->getClone();
                        $tempRupaSearch->setStartNum(($i - 1) * $currentRupaSearch->numToShow);
                        $queryString = $tempRupaSearch->getRupaQueryStringForXHTML();
?>
                    <li>
                        <a href="<?php print getSiteRootURL().'/site/scripts/google_results.php?' . $queryString; ?>"><?php print $i; ?></a>
                    </li>
<?php
                    }
                    else {
?>
                    <li>
                        <span><?php print $i; ?></span>
                    </li>
<?php
                    }

                    // Stop showing page numbers if the rest of the results are omitted.
                    if ($i == $currentPage && $resultsOmitted) {
                        break;
                    }
                }
?>
                </ul>
<?php
                if ($currentRupaSearch->nextPage || (($currentRupaSearchResult->startNumber + $currentRupaSearch->numToShow) < $currentRupaSearchResult->documentsFound && ($currentRupaSearchResult->documentsFound - $currentRupaSearch->numToShow) > 0)) {
                    $tempRupaSearch = $currentRupaSearch->getClone();
                    $tempRupaSearch->setStartNum($currentRupaSearchResult->endNumber);
                    $queryString = $tempRupaSearch->getRupaQueryStringForXHTML();
?>
                <a href="<?php print getSiteRootURL() . '/site/scripts/google_results.php?' . $queryString; ?>" class="button float_right">Next</a>
<?php
                }
            }
?>
                </div>
<?php
        }
    }
?>
                <form method="get" action="<?php print getSiteRootURL() . buildSearchResultsURL() ?>" class="form form__inline form__block">
                        <label for="searchAgain">Search again:</label>
                        <input type="text" id="searchAgain" name="q" maxlength="256" value="<?php print $currentRupaSearch->getFullQueryForXHTML(); ?>" class="text">
                        <input type="submit" name="btnG" value="Search" class="button button__info">
                </form>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/site/includes/closing.php';
?>