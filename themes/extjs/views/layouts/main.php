<html>
<head>
    <title><?php echo CHtml::encode(Yii::app()->name); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/ext340/resources/css/ext-all.css" /> 
    <style type="text/css">            
            .x-tree-node-icon {
            background:transparent none repeat scroll 0 0;
            width:0 !important;
            }

            .combo-item {
            font: normal 12px tahoma, arial, helvetica, sans-serif;
            line-height:150%;
            padding:5px 20px 5px 10px;
            border:1px solid #fff;
            border-bottom: 1px solid #eee;
            white-space: normal;
            color:#555;
            }
            #header {
                background: #7F99BE url(images/headbg.gif) repeat-x center !important;
            }           
            #header h1 {
                font-size: 16px;
                color:#fff;
                font-weight: normal;
                padding: 8px 10px;
                float:left;
                width:600px !important;
            }
            #usrlogin {
            color: #fff;
            float: right;
            margin: 10px;
            }

            #usrlogin a {
            color: #fff;
            }
             #loading-mask{
        position:absolute;
        left:0;
        top:0;
        width:100%;
        height:100%;
        z-index:20000;
        background-color:white;
    }
    #loading{
        position:absolute;
        left:45%;
        top:40%;
        padding:2px;
        z-index:20001;
        height:auto;
    }
    #loading a {
        color:#225588;
    }
    #loading .loading-indicator{
        background:white;
        color:#444;
        font:bold 13px tahoma,arial,helvetica;
        padding:10px;
        margin:0;
        height:auto;
    }
    #loading-msg {
        font: normal 10px arial,tahoma,sans-serif;
    }

    #usrlogin,
    #usrlogin a{
        color: #fff;
		font-size: 12px;
        padding: 0 0 10px 0;
    }

    #header h1{
        color: #E0E0E0;
        font-weight: bold;
    }
x-form-field-wrap {display:inline}
    </style>
</head>
<body>
    <div id="loading-mask" style=""></div>
<div id="loading">
    <div class="loading-indicator"><img src="js/ext340/resources/images/default/shared/blue-loading.gif" width="32" height="32" style="margin-right:8px;float:left;vertical-align:top;" alt="" />Aplikasi <?php echo CHtml::encode(Yii::app()->name); ?></a><br /><span id="loading-msg">Loading styles and images...</span></div>
</div>
<div id="header">
    <h1><strong><?php echo CHtml::encode(Yii::app()->name); ?></strong></h1>
    <span id="usrlogin">Welcome :  <?php echo CHtml::encode(Yii::app()->user->name); ?>  | <a href="index.php/site/logout" class="lout">Logout</a></span>

</div>

        <script type="text/javascript">document.getElementById('loading-msg').innerHTML = 'Loading Core API...';</script>

        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/ext340/adapter/ext/ext-base-debug.js"></script>
        <script type="text/javascript">document.getElementById('loading-msg').innerHTML = 'Loading UI Components...';</script>

        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/ext340/ext-all-debug.js"></script>
        <script type="text/javascript">document.getElementById('loading-msg').innerHTML = 'Initializing...';</script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/sidebar.js"></script>
        <?php
        
            $templatePath = './js/view/';
            $files = scandir($templatePath);
            
            foreach ($files as $file)
            {
                if (is_file($templatePath . '/' . $file))
                { 
        ?>                            
                    <script type="text/javascript" src="<?php echo($templatePath.$file); ?>"></script>
        <?                
                }
            }
        ?>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/mainpanel.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/app.js"></script>

</body>
</html>