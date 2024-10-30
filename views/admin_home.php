<?php
$blogbuzztime = BlogBuzzTime::getInstance();
$parameters = $blogbuzztime->getParameters();
$updated = false;
if(!isset($parameters['creditRoomId']))
{
    $parameters['creditRoomId'] = 0;
}

if (!isset($parameters['forbidden_urls']))
{
    $parameters['forbidden_urls'] = array();
    $parameters['nbItems'] = 5;
    $parameters['minReaders'] = 1;
    $parameters['suffixUrl'] = '#blogbuzztime';
    $parameters['oneItem'] = __('reader', 'blogbuzztime');
    $parameters['manyItem'] = __('readers', 'blogbuzztime');
    $parameters['lang'] = __('defaultLang', 'blogbuzztime');
    $parameters['bbtTotalReaders'] = __('online users', 'blogbuzztime');
    $parameters['bbtTotalReader'] = __('online user', 'blogbuzztime');

    $blogbuzztime->setParameters($parameters);
}
else if (!isset($parameters['bbtTotalReaders']))
{
    $parameters['bbtTotalReaders'] = __('online users', 'blogbuzztime');
    $parameters['bbtTotalReader'] = __('online user', 'blogbuzztime');
    $blogbuzztime->setParameters($parameters);
}
else if (!is_array($parameters['forbidden_urls']))
{
    $parameters['forbidden_urls'] = array();
    $blogbuzztime->setParameters($parameters);
}
if (!isset($parameters['bbtFloatingBox']))
{
    $parameters['bbtFloatingBox'] = false;
    $parameters['bbtFloatingBoxStyle'] = 'black';
    $blogbuzztime->setParameters($parameters);
}
if(isset($_POST['creditRoomId']))
{
    $parameters['creditRoomId'] = (int)$_POST['creditRoomId'];
    $blogbuzztime->setParameters($parameters);
}

if (isset($_POST['bbtFloatingBox']) && isset($_POST['bbthashkey']) && $_POST['bbthashkey'] == $blogbuzztime->getHashKey()){
    $parameters['bbtFloatingBox'] = $_POST['bbtFloatingBox'];
    $parameters['bbtFloatingBoxStyle'] = $_POST['bbtFloatingBoxStyle'];
    $blogbuzztime->setParameters($parameters);
}
if (isset($_POST['forbidden_urls']) && isset($_POST['bbthashkey']) && $_POST['bbthashkey'] == $blogbuzztime->getHashKey())
{
    $newArray = array();
    foreach ($_POST['forbidden_urls'] as $url)
    {
        $url = trim($url);

        if (strlen($url) > 0)
            $newArray[$url] = $url;
    }

    $parameters['forbidden_urls'] = $newArray;
    $blogbuzztime->setParameters($parameters);
    $updated = true;
}
else if (isset($_POST['nbItems']) && isset($_POST['bbthashkey']) && $_POST['bbthashkey'] == $blogbuzztime->getHashKey())
{
    $parameters['nbItems'] = (int) $_POST['nbItems'];
    $parameters['minReaders'] = (int) $_POST['minReaders'];
    $parameters['suffixUrl'] = $_POST['suffixUrl'];
    $parameters['oneItem'] = $_POST['oneItem'];
    $parameters['manyItem'] = $_POST['manyItem'];
    $parameters['lang'] = $_POST['language'];
    $blogbuzztime->setParameters($parameters);
    $updated = true;
}
else if (isset($_POST['bbtTotalReaders']) && isset($_POST['bbthashkey']) && $_POST['bbthashkey'] == $blogbuzztime->getHashKey())
{
    $parameters['bbtTotalReaders'] = $_POST['bbtTotalReaders'];
    $parameters['bbtTotalReader'] = $_POST['bbtTotalReader'];
    $blogbuzztime->setParameters($parameters);
}

$creditRooms = json_decode(file_get_contents('http://www.blogbuzztime.com/cr/list'));
$creditRooms = $creditRooms->creditRooms;
?>
<div id="wphead">
    <h2>BlogBuzzTime</h2>
</div>
<?php
if ($updated)
{
    ?>
    <div class="notice info">
    <?php _e('Configuration saved.', 'blogbuzztime'); ?>
        <strong><?php _e('You can now add widget to sidebar'); ?></strong>
    </div>
<?php } ?>
<form method="post" id="bbt-linkPostForm">
    <input type="hidden" name="bbthashkey" value="<?php echo $blogbuzztime->getHashKey() ?>" />
    <div id="poststuff">
        <div class="metabox-holder columns-2">
            <div>
                <div class="stuffbox">
                    <h3><label for="link_name"><?php _e('Ignore an url', 'blogbuzztime'); ?></label></h3>
                    <div class="inside">
                        <input type="text" name="forbidden_urls[]" size="30" tabindex="1" value="" />
                        <input type="submit" value="Add" />
                        <p><?php _e('Example', 'blogbuzztime'); ?>&nbsp;: <em>/576-my-article</em></p>
                    </div>
                </div>

            </div><!-- /post-body-content -->
        </div>
    </div>
<?php
if (count($parameters['forbidden_urls']) > 0)
{
    ?>
        <input type="hidden" name="bbthashkey" value="<?php echo $blogbuzztime->getHashKey() ?>" />
        <div id="poststuff">
            <div class="metabox-holder columns-2">
                <div>
                    <div class="stuffbox">
                        <h3><label for="link_name"><?php _e('Hidden urls', 'blogbuzztime'); ?>:</label></h3>
                        <div class="inside">
    <?php
    $i = 0;
    foreach ($parameters['forbidden_urls'] as $url)
    {
        ++$i;
        echo '
                                <div  id="bbt-url-' . $i . '">
                                    <input type="text" name="forbidden_urls[]" value="' . $url . '"   id="bbt-url-field-' . $i . '"/>
                                    <input type="button" value="[ x ]" onclick="bbt_remove_link(' . $i . ')" />
                                </div>
                                ';
    }
    ?>
                        </div>
                    </div>
                </div><!-- /post-body-content -->
            </div>
        </div>
<?php } ?>
    <script>
        function bbt_remove_link(linkIndex){
            document.getElementById('bbt-url-field-' + linkIndex).value = '';
            document.getElementById('bbt-url-' + linkIndex).style = 'display:none;';
            document.getElementById('bbt-linkPostForm').submit();
        }
    </script>
</form>
<form method="post">
    <input type="hidden" name="bbthashkey" value="<?php echo $blogbuzztime->getHashKey() ?>" />
    <div id="poststuff">
        <div class="metabox-holder columns-2">
            <div>
                <div class="stuffbox">
                    <h3><label for="link_name"><?php _e('Other parameters', 'blogbuzztime'); ?></label></h3>
                    <div class="inside">
                        <p><?php _e('Number of links to show', 'blogbuzztime'); ?> <em>(0 = <?php _e('show all', 'blogbuzztime'); ?>)</em></p>
                        <input type="text" name="nbItems" size="30" tabindex="1" value="<?php echo $parameters['nbItems']; ?>" />
                    </div>
                    <div class="inside">
                        <p><?php _e('Mininum number of readers to show a link', 'blogbuzztime'); ?>: <em>(0 = <?php _e('no min'); ?> )</em></p>
                        <input type="text" name="minReaders" size="30" tabindex="1" value="<?php echo $parameters['minReaders']; ?>" />
                    </div>
                    <div class="inside">
                        <p><?php _e('Add suffix to url', 'blogbuzztime'); ?>: </p>
                        <input type="text" name="suffixUrl" size="30" tabindex="1" value="<?php echo $parameters['suffixUrl']; ?>" />
                    </div>
                    <div class="inside">
                        <p><?php _e('Wording for "Reader" (singular)', 'blogbuzztime'); ?>: </p>
                        <input type="text" name="oneItem" size="30" tabindex="1" value="<?php echo $parameters['oneItem']; ?>" />
                    </div>
                    <div class="inside">
                        <p><?php _e('Wording for "Readers" (plural)', 'blogbuzztime'); ?>: </p>
                        <input type="text" name="manyItem" size="30" tabindex="1" value="<?php echo $parameters['manyItem']; ?>" />
                    </div>
                    <div class="inside">
                        <p><?php _e('What\'s your language?', 'blogbuzztime'); ?></p>
                        <div>
                            <input type="radio" name="language" value="en" id="language-en" <?php
if ($parameters['lang'] == 'en')
{
    ?>selected checked<?php } ?>/>
                            <label for="language-en" >English</label>
                        </div>
                        <div>
                            <input type="radio" name="language" value="fr" id="language-fr"  <?php
                            if ($parameters['lang'] == 'fr')
                            {
    ?>selected checked<?php } ?>/>
                            <label for="language-fr">Français</label>
                        </div>
                    </div>
                    <div class="inside">
                        <input type="submit" value="<?php _e('Save', 'blogbuzztime'); ?> " />
                    </div>
                </div>

            </div><!-- /post-body-content -->
        </div>
    </div>
</form>
<form method="post">
<input type="hidden" name="bbthashkey" value="<?php echo $blogbuzztime->getHashKey() ?>" />
    <div id="poststuff">
        <div class="metabox-holder columns-2">
            <div>
                <div class="stuffbox">
                    <h3><label for="link_name"><?php _e('Widget current online users', 'blogbuzztime'); ?></label></h3>
                    <div class="inside">
                        <p><?php _e('Wording for online user', 'blogbuzztime'); ?></p>
                        <input type="text" name="bbtTotalReader" size="30" tabindex="1" value="<?php echo $parameters['bbtTotalReader']; ?>" />
                    </div>
                    <div class="inside">
                        <p><?php _e('Wording for online users', 'blogbuzztime'); ?></p>
                        <input type="text" name="bbtTotalReaders" size="30" tabindex="1" value="<?php echo $parameters['bbtTotalReaders']; ?>" />
                    </div>
                    <div class="inside">
                        <input type="submit" value="<?php _e('Save', 'blogbuzztime'); ?> " />
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<form method="post">
<input type="hidden" name="bbthashkey" value="<?php echo $blogbuzztime->getHashKey() ?>" />
    <div id="poststuff">
        <div class="metabox-holder columns-2">
            <div>
                <div class="stuffbox">
                    <h3><label for="link_name"><?php _e('Afficher les widgets en floating box', 'blogbuzztime'); ?></label></h3>
                    <div class="inside">
                        <p><?php _e('Activer la floating box', 'blogbuzztime'); ?></p>
                        <input type="radio" name="bbtFloatingBox" <?php if ($parameters['bbtFloatingBox']) { ?> selected checked <?php } ?>value="1" /><?php  _e('Activer', 'blogbuzztime'); ?>
                        <input type="radio" name="bbtFloatingBox" <?php if (!$parameters['bbtFloatingBox']) { ?> selected checked <?php } ?>value="0" /><?php  _e('Désactiver', 'blogbuzztime'); ?>

                    </div>
                    <div class="inside">
                        <p><?php _e('Style de la floating box', 'blogbuzztime'); ?></p>
                        <input type="radio" name="bbtFloatingBoxStyle" <?php if ($parameters['bbtFloatingBoxStyle'] == 'white') { ?> selected checked <?php } ?>value="white" /><?php  _e('Blanc', 'blogbuzztime'); ?>
                        <input type="radio" name="bbtFloatingBoxStyle" <?php if ($parameters['bbtFloatingBoxStyle'] == 'black') { ?> selected checked <?php } ?>value="black" /><?php  _e('Noir', 'blogbuzztime'); ?>
                    </div>
                    <div class="inside">
                        <input type="submit" value="<?php _e('Save', 'blogbuzztime'); ?> " />
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<form method="post">
    <div id="poststuff">
        <div class="metabox-holder columns-2">
            <div>
                <div class="stuffbox">

                    <h3><label for="link_name"><?php _e('Categorie des echanges liens partenaires', 'blogbuzztime'); ?></label></h3>
                    <div class="inside">
                        <p><?php _e('Choisissez une thematique pour activer les echanges de liens avec des blogs de meme thematique', 'blogbuzztime'); ?></p>
                        <select name="creditRoomId" id="creditRooms">
                            <option value="0">Désactiver l'échange de liens</option>
                            <?php
                                foreach($creditRooms as $creditRoom)
                                {
                                    echo '<option ' . ($parameters['creditRoomId'] == $creditRoom->id?'selected':'') . ' value="' . $creditRoom->id . '">' . $creditRoom->title . ' (' . $creditRoom->nbSites . ')</option>';
                                }
                            ?>
                        </select>
                        <p><a href="http://www.blogbuzztime.com/<?php echo $parameters['lang']; ?>#creditrooms" target="_blank">[ <?php _e('En savoir plus sur l\'echange de liens BlogBuzzTime', 'blogbuzztime'); ?> ]</a></p>
                        <?php if ( $parameters['creditRoomId'] > 0 ) { ?>
                            <h3><?php _e('Vous avez', 'blogbuzztime'); ?> <span id="currentCredits">0</span> <?php _e('Credits', 'blogbuzztime'); ?></h3>
                            <div>
                                <strong><?php _e('RSS feed for your partner\'s links', 'blogbuzztime'); ?></strong><br />
                                <a id="rssLink" href="" target="_blank"></a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="inside">
                        <input type="submit" value="<?php _e('Save', 'blogbuzztime'); ?> " />
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div>
    <h1 id="bbtTitleStatus"><?php _e('BlogBuzzTime Plateform health', 'blogbuzztime'); ?>:</h1>
    <div id="bbtresult" style="text-align:center;display: inline-block;">
        <img src="<?php echo $blogbuzztime->getUrl(); ?>/images/ajax-loader.gif" alt="" />
    </div>
    <style>
        .bbterror{ color: red; font-weight:bold;}
        .bbtsuccess{ color:green; font-weight:bold;}
        #bbtallok{
            width: 400px;
            border: 1px solid #D8D8D8;
            padding: 10px;
            border-radius: 5px;
            font-family: Arial;
            font-size: 11px;
            text-transform: uppercase;
            background-color: rgb(236, 255, 216);
            color: green;
            text-align: center;
        }
        #bbtdownerror{
            width: 400px;
            border: 1px solid #D8D8D8;
            padding: 5px;
            border-radius: 5px;
            font-family: Arial;
            font-size: 11px;
            text-transform: uppercase;
            background-color: rgb(255, 249, 242);
            color: rgb(211, 0, 0);
            text-align: center;
        }
    </style>
    <script>
        function bbtOkPlateform(){
            jQuery('#bbtresult').html('<div id="bbtallok"><?php _e('All is ok', 'blogbuzztime'); ?> :)</div>');
            jQuery('#bbtTitleStatus').addClass('bbtsuccess');
        }
        function bbtErrorPlateform(){
            jQuery('#bbtresult').html('<div id="bbtdownerror"><?php _e('BlogBuzzTime is down', 'blogbuzztime'); ?> :s</div>');
            jQuery('#bbtTitleStatus').addClass('bbterror');
        }
        jQuery(document).ready(function () {
            try{
                jQuery.get('http://www.blogbuzztime.com/check', function(d){
                    if(d!='1')
                        bbtErrorPlateform();
                    else
                        bbtOkPlateform();
                });
            }catch(e){
                bbtErrorPlateform();
            }
            var data =  {location: 'http://<?php echo $_SERVER['SERVER_NAME'] ?>/', lang: '<?php echo $parameters['lang']; ?>'};
                jQuery.post('http://www.blogbuzztime.com/cr/siteInfo', data, function(d){
                    var siteInfos = eval('(' + d + ')');
                    document.getElementById('currentCredits').innerHTML = siteInfos.credits;
                    document.getElementById('rssLink').href = siteInfos.rssFeed;
                    document.getElementById('rssLink').innerHTML = siteInfos.rssFeed;
                });

        });
    </script>
</div>
<div style="font-size:11px; text-align:right;color:#666; padding-right: 15px;">
<?php _e('Plugin by', 'blogbuzztime'); ?> <a href="http://blogbuzztime.com/<?php _e('defaultLang', 'blogbuzztime'); ?>/" target="_blank" style="color:#666">BlogBuzzTime.com</a>
</div>