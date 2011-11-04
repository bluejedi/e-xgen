<?php

/**
 * GxController class file.
 *
 * @author Rodrigo Coelho <giix@rodrigocoelho.com.br>
 * @link http://rodrigocoelho.com.br/giix/
 * @copyright Copyright &copy; 2010 Rodrigo Coelho
 * @license http://rodrigocoelho.com.br/giix/license/ New BSD License
 */

/**
 * GxController is the base class for the generated controllers.
 *
 * @author Rodrigo Coelho <giix@rodrigocoelho.com.br>
 * @since 1.0
 */
abstract class GxController extends Controller {
    /*
    public function filters()
    {
        return array(
        'rights',
        );
    }*/
/*
public function allowedActions()
{
return 'index, suggestedTags';
}*/

	/**
	 * @var string The layout for the controller view.
	 */
	public $layout = '//layouts/column2';
        //public $layout = '//layouts/column1';
        public $menuz=array();
        public $menuzrs=array();
	/**
	 * @var array Context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();
	/**
	 * @var array The breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param mixed $id the ID of the model to be loaded
	 * @param string $modelClass the model class name
	 * @return GxActiveRecord the loaded model
	 */
	public function loadModel($id, $modelClass) {
		$model = GxActiveRecord::model($modelClass)->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel $model the model to be validated
	 * @param string $form the name of the form
	 */
	protected function performAjaxValidation($model, $form) {
		if (Yii::app()->request->isAjaxRequest && $_POST['ajax'] == $form) {
			echo GxActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * Finds the related primary keys specified in the form post.
	 * Only for HAS_MANY and MANY_MANY relations.
	 * @param array $form The post data.
	 * @param array $relations A list of model relations.
	 * @return array An array where the keys are the relation names (string) and the values arrays with the related model primary keys (int|string) or composite primary keys (array with pk name (string) => pk value (int|string)).
	 * Example of returned data:
	 * array(
	 *   'categories' => array(1, 4),
	 *   'tags' => array(array('id1' => 3, 'id2' => 7), array('id1' => 2, 'id2' => 0)) // composite pks
	 * )
	 * An empty array is returned in case there is no related pk data from the post.
	 */
	protected function getRelatedData($form, $relations) {
		$relatedPk = array();
		foreach ($relations as $relationName => $relationData) {
			if (isset($form[$relationName]) && (($relationData[0] == GxActiveRecord::HAS_MANY) || ($relationData[0] == GxActiveRecord::MANY_MANY)))
				$relatedPk[$relationName] = $form[$relationName] === '' ? null : $form[$relationName];
		}
		return $relatedPk;
	}
        
        public function renderJson($model, $total){
                
                $argh = array();
                
                foreach($model AS $dodol)
                //foreach($model AS $SJP)
                {
                       $argh[] = $dodol->getAttributes();
                };
                $jsonresult = '{"total":"'.$total.'","results":'.json_encode($argh).'}';
                Yii::app()->end($jsonresult);
        }
        
        public function renderJson2($model){
            $dataProvider=$model;
                
                //$total = $dataProvider->itemCount;
                
                $argh = array();
                
                foreach($dataProvider->getData() AS $dodol)
                //foreach($model AS $SJP)
                {
                    print_r($dodol->getAttributes());   
                    $argh[] = $dodol->getAttributes();
                };
                $jsonresult = '{"total":"'.$total.'","results":'.json_encode($argh).'}';
                Yii::app()->end($jsonresult);
        }
        
}