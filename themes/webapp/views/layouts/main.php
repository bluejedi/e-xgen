<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>                
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/web-app-theme/base.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/web-app-theme/themes/default/style.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/web-app-theme/override.css" type="text/css" media="screen"/>
                <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" type="text/css" media="screen"/>
	</head>
	<body>
		<div id="container">
			<div id="header">
				<h1>
					<a href="<?php echo Yii::app()->homeUrl; ?>"><?php echo Yii::app()->name; ?></a>
				</h1>
				<div id="user-navigation">
					<?php $this->widget('zii.widgets.CMenu', array(
						'items' => array(
							array('label' => 'Home', 'url' => Yii::app()->homeUrl),
                                                        array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                                                        array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
							//array('label' => 'Logout', 'url' => array('/site/logout'), 'linkOptions' => array('class' => 'logout'), 'visible' => !Yii::app()->user->isGuest),
						),
						'htmlOptions' => array(
							'class' => 'wat-cf',
						),
					)); ?>
				</div>
				<div id="main-navigation">
					<?php $this->widget('zii.widgets.CMenu', array(
						'items'=>array(
                                                        //array('label'=>'Home', 'url'=>array('/site/index')),
                                                        //array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
                                                        array('label'=>'Proposal', 'url'=>array('/giix/proposal')),
                                                        array('label'=>'ProposalKe', 'url'=>array('/giix/proposalKe')),
                                                        array('label'=>'Klasifikasi', 'url'=>array('/giix/klasifikasiDetail')),
                                                        array('label'=>'Manfaat', 'url'=>array('/giix/manfaatAsuransi')),
                                                    array('label'=>'Other', 'url'=>array('/giix/otherInfo')),
                                                        //array('label'=>'Contact', 'url'=>array('/site/contact')),
                                                        //array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                                                        //array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                                                ),
						'htmlOptions' => array(
							'class' => 'wat-cf',
						),
					)); ?>
				</div>
                            
			</div>
			<div id="wrapper" class="wat-cf">
				<?php echo $content; ?>
			</div>
		</div>
	</body>
</html>