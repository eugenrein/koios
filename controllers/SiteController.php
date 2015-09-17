<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\UploadForm;
use app\models\ContactForm;
use app\helpers\Enricher;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'main_background';
        $model = new UploadForm();

        return $this->render('index', ['model' => $model]);
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionUpload()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->upload()) {
                // file is uploaded successfully
                return ['success' => true, 'filename' => $model->filename];
            }
        }

        return ['error' => true];
    }

    public function actionView($file) {
        $file = str_replace(['..', '/', '\\'], '', $file);
        $file_path = \Yii::getAlias('@webroot') . "/uploads/{$file}";
        
        if (file_exists($file_path) && 
            $this->isXML($file_path) &&
            false !== ($model = file_get_contents($file_path)) &&
            false !== ($body = $this->getXMLBody($model))) {
            
            $purified_body = \yii\helpers\HtmlPurifier::process($body);
            $purified_body = Enricher::process($purified_body);

            $title = $this->getXMLTitle($model);
            $purified_title = \yii\helpers\HtmlPurifier::process($title);

            return $this->render('view', [
                'content' => $purified_body,
                'title' => $title
            ]);
        }

        return $this->redirect(['site/index']);
    }

    public function actionDownload($file) {
        $file = str_replace(['..', '/', '\\'], '', $file);
        $file_path = \Yii::getAlias('@webroot') . "/uploads/{$file}";
        
        if (file_exists($file_path) && 
            $this->isXML($file_path) &&
            false !== ($model = file_get_contents($file_path)) &&
            false !== ($body = $this->getXMLBody($model))) {
            
            $enriched_body = Enricher::process_xml($body);

            Yii::$app->response->sendContentAsFile(
                $this->replaceXMLBody($model, $enriched_body),
                $file,
                ['mimeType' => 'text/xml', 'inline' => true]
            );

            return;
        }

        return $this->notFound();
    }

    private function isXML($file) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_info = finfo_file($finfo, $file);
        finfo_close($finfo);

        return ($file_info == 'application/xml' ||
                    $file_info == 'text/xml');
    }

    private function getXMLBody($xml_file) {
        $doc = new \DOMDocument();
        $doc->loadXML($xml_file);
        $list = $doc->getElementsByTagName('body');

        if ($list->length) {
            $body = $list->item(0);
            return $body->ownerDocument->saveXML($body);
        }

        return false;
    }

    private function replaceXMLBody($xml_file, $body_content) {
        $replacement = "$1{$body_content}$2";

        $doc = preg_replace('~(<body[^>]*>).*(</body>)~is', $replacement, $xml_file, -1, $count);

        return $doc;
    }

    private function getXMLTitle($xml_file) {
        $doc = new \DOMDocument();
        $doc->loadXML($xml_file);
        $list = $doc->getElementsByTagName('title');

        if ($list->length) {
            $title = $list->item(0);
            return $title->textContent;
        }

        return false;
    }

    private function notFound($message = null) {
        if (!$message) {
            $message = Yii::t('app', 'The requested page does not exist.');
        }

        throw new NotFoundHttpException($message);
    }
}
