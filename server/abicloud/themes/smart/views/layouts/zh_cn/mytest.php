<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Server API</title>
  <link href='<?php echo Yii::app()->theme->baseUrl; ?>/swagger/css/css.css' rel='stylesheet' type='text/css'/>
  <link href='<?php echo Yii::app()->theme->baseUrl; ?>/swagger/css/reset.css' media='screen' rel='stylesheet' type='text/css'/>
  <link href='<?php echo Yii::app()->theme->baseUrl; ?>/swagger/css/screen.css' media='screen' rel='stylesheet' type='text/css'/>
  <link href='<?php echo Yii::app()->theme->baseUrl; ?>/swagger/css/reset.css' media='print' rel='stylesheet' type='text/css'/>
  <link href='<?php echo Yii::app()->theme->baseUrl; ?>/swagger/css/screen.css' media='print' rel='stylesheet' type='text/css'/>
  <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/swagger/lib/shred.bundle.js"></script>
  <script src='<?php echo Yii::app()->theme->baseUrl; ?>/swagger/lib/jquery-1.8.0.min.js' type='text/javascript'></script>
  <script src='<?php echo Yii::app()->theme->baseUrl; ?>/swagger/lib/jquery.slideto.min.js' type='text/javascript'></script>
  <script src='<?php echo Yii::app()->theme->baseUrl; ?>/swagger/lib/jquery.wiggle.min.js' type='text/javascript'></script>
  <script src='<?php echo Yii::app()->theme->baseUrl; ?>/swagger/lib/jquery.ba-bbq.min.js' type='text/javascript'></script>
  <script src='<?php echo Yii::app()->theme->baseUrl; ?>/swagger/lib/handlebars-1.0.0.js' type='text/javascript'></script>
  <script src='<?php echo Yii::app()->theme->baseUrl; ?>/swagger/lib/underscore-min.js' type='text/javascript'></script>
  <script src='<?php echo Yii::app()->theme->baseUrl; ?>/swagger/lib/backbone-min.js' type='text/javascript'></script>
  <script src='<?php echo Yii::app()->theme->baseUrl; ?>/swagger/lib/swagger.js' type='text/javascript'></script>
  <script src='<?php echo Yii::app()->theme->baseUrl; ?>/swagger/lib/swagger-client.js' type='text/javascript'></script>
  <script src='<?php echo Yii::app()->theme->baseUrl; ?>/swagger/swagger-ui.js' type='text/javascript'></script>
  <script src='<?php echo Yii::app()->theme->baseUrl; ?>/swagger/lib/highlight.7.3.pack.js' type='text/javascript'></script>

  <!-- enabling this will enable oauth2 implicit scope support -->
  <script src='<?php echo Yii::app()->theme->baseUrl; ?>/swagger/lib/swagger-oauth.js' type='text/javascript'></script>
  <script type="text/javascript">
    $(function () {
      var url = window.location.search.match(/url=([^&]+)/);
      if (url && url.length > 1) {
        url = url[1];
      } else {
        //url = "data/swagger-oranger-full.json";
        url = "<?php echo Yii::app()->theme->baseUrl; ?>/swagger/data/abiktv.json";
      }
      window.swaggerUi = new SwaggerUi({
        url: url,
        dom_id: "swagger-ui-container",
        supportedSubmitMethods: ['get', 'post', 'put', 'delete'],
        onComplete: function(swaggerApi, swaggerUi){
          log("Loaded SwaggerUI");
          if(typeof initOAuth == "function") {
            /*
            initOAuth({
              clientId: "your-client-id",
              realm: "your-realms",
              appName: "your-app-name"
            });
            */
          }
          $('pre code').each(function(i, e) {
            hljs.highlightBlock(e)
          });
        },
        onFailure: function(data) {
          log("Unable to Load SwaggerUI");
        },
        docExpansion: "none",
        sorter : "alpha"
      });

      function addApiKeyAuthorization() {
        var key = $('#input_apiKey')[0].value;
        log("key: " + key);
        if(key && key.trim() != "") {
            log("added key " + key);
            window.authorizations.add("API_KEY", new ApiKeyAuthorization("API_KEY", key, "query"));
        }
      }

      $('#input_apiKey').change(function() {
        addApiKeyAuthorization();
      });

      function addRoomKeyAuthorization() {
        var key = $('#input_roomKey')[0].value;
        log("key: " + key);
        if(key && key.trim() != "") {
            log("added key " + key);
            window.authorizations.add("X-KTV-Room-ID", new ApiKeyAuthorization("X-KTV-Room-ID", key, "header"));
        }
      }
      function addTokenAuthorization() {
        var key = $('#input_token')[0].value;
        log("key: " + key);
        if(key && key.trim() != "") {
            log("added key " + key);
            window.authorizations.add("X-KTV-User-Token", new ApiKeyAuthorization("X-KTV-User-Token", key, "header"));
        }
      }
      
      $('#input_token').change(function() {
        addTokenAuthorization();
      });
      
      function addVendorApplication() {
          window.authorizations.add("X-KTV-Application-Name", new ApiKeyAuthorization("X-KTV-Application-Name", "eec607d1f47c18c9160634fd0954da1a", "header"));
          window.authorizations.add("X-KTV-Vendor-Name", new ApiKeyAuthorization("X-KTV-Vendor-Name", "1d55af1659424cf94d869e2580a11bf8", "header"));
      }
      // if you have an apiKey you would like to pre-populate on the page for demonstration purposes...
      /*
        var apiKey = "myApiKeyXXXX123456789";
        $('#input_apiKey').val(apiKey);
        addApiKeyAuthorization();
      */

        $('#apikey_button.auth_icon').attr("src","<?php echo Yii::app()->theme->baseUrl; ?>/swagger/images/apikey.jpeg");

      window.swaggerUi.load();
      
      addVendorApplication();
      addTokenAuthorization();
      //addRoomKeyAuthorization();
  });
  </script>
</head>

<body class="swagger-section">
<div id='header'>
  <div class="swagger-ui-wrap">
    <a id="logo" href="#">Server API</a>
    <form id='api_selector'>
      <!--  div class='input'><input placeholder="" id="input_baseUrl" name="baseUrl" type="text"/></div -->
        <?php echo $content; ?>
      <!-- div class='input'><a id="explore" href="#">Explore</a></div -->
    </form>
  </div>
</div>

<div id="message-bar" class="swagger-ui-wrap">&nbsp;</div>
<div id="swagger-ui-container" class="swagger-ui-wrap"></div>
</body>
</html>
