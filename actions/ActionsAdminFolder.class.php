<?php

use Symfony\Component\HttpFoundation\Request;

class ActionsAdminFolder extends ActionsAdminBase
{
    private static $instance = false;
    
    protected function __construct() {}
    /**
     * 
     * @return ActionsAdminFolder
     */
    public static function getInstance(){
        if(self::$instance === false) self::$instance = new ActionsAdminFolder();
        
        return self::$instance;
    }
    
    public function action(Request $request){
        switch($request->get('action'))
        {
            /*listdos actions*/
            case 'addFolder':
                FolderAdmin::getInstance()->add($request->request->get('title'), $request->request->get('parent'));
                break;
            case 'deleteFolder':
                FolderAdmin::getInstance($request->query->get('folder_id'))->delete();
                break;
            
            /*association : RO REVIEW*/
            case 'deleteAssociatedContent':
                AssociatedContentAdmin::getInstance()->delete($request->query->get('associatedContent'));
                break;
            case 'addAssociatedContent':
                AssociatedContentAdmin::getInstance()->add($request->query->get('contenu'), 0, $request->query->get('id'));
                break;

            case 'deleteAssociatedFeature':
                AssociatedFeatureAdmin::getInstance()->delete($request->query->get('associatedFeature'));
                break;
            case 'addAssociatedFeature':
                AssociatedFeatureAdmin::getInstance()->add($request->query->get('feature'), $request->query->get('id'));
                break;

            case 'deleteAssociatedVariant':
                AssociatedVariantAdmin::getInstance()->delete($request->query->get('associatedVariant'));
                break;
            case 'addAssociatedVariant':
                AssociatedVariantAdmin::getInstance()->add($request->query->get('variant'), $request->query->get('id'));
                break;

            /*information & description*/
            case 'changeInformation':
                FolderAdmin::getInstance($request->request->get('id'))->editInformation($request->request->get('ligne'), $request->request->get('parent'), $request->request->get('lien'));
                break;
            case 'changeDescription':
                FolderAdmin::getInstance($request->request->get('id'))->editDescription($request->request->get('lang'), $request->request->get('titre'), $request->request->get('chapo'), $request->request->get('description'), $request->request->get('postscriptum'), $request->request->get('url'));
                break;

            /*attachement : picture*/
            case 'addPicture':
                FolderAdmin::getInstance($request->request->get('id'))->addPicture();
                break;
            case 'editPicture':
                FolderAdmin::getInstance($request->request->get('id'))->updateImage($this->getImages($request, FolderAdmin::getInstance($request->request->get('id'))), $request->request->get('lang'));
                break;
            case 'deletePicture':
                FolderAdmin::getInstance($request->query->get('id'))->deleteImage($request->query->get('picture'), $request->query->get('lang'));
                break;
            case 'modifyPictureClassement':
                FolderAdmin::getInstance($request->query->get('id'))->modifyImageOrder($request->query->get('picture'), $request->query->get('will'), $request->query->get('lang'));
                break;
            
            /*attachement : document*/
            case 'addDocument':
                FolderAdmin::getInstance($request->request->get('id'))->addDocument();
                break;
            case 'editDocument':
                FolderAdmin::getInstance($request->request->get('id'))->updateDocument($this->getDocuments($request, FolderAdmin::getInstance($request->request->get('id'))), $request->request->get('lang'));
                break;
            case 'deleteDocument':
                FolderAdmin::getInstance($request->query->get('id'))->deleteDocument($request->query->get('document'), $request->query->get('lang'));
                break;
            case 'modifyDocumentClassement':
                FolderAdmin::getInstance($request->query->get('id'))->modifyDocumentOrder($request->query->get('document'), $request->query->get('will'), $request->query->get('lang'));
                break;
        }
    }
    
    protected function getImages(Request $request, \FolderAdmin $folder)
    {
        $return = array();
        
        if($folder->id == ''){
            return $return;
        }
        
        $query = 'select id from '.Image::TABLE.' where rubrique='.$folder->id;

        
        $return = $this->extractResult($request, $folder->query_liste($query), array(
            "titre" => "photo_titre_",
            "chapo" => "photo_chapo_",
            "description" => "photo_description_"
        ));
        
        return $return;
    }
    
    protected function getDocuments(Request $request, \FolderAdmin $folder)
    {
        $return = array();
        
        if($folder->id == ''){
            return array();
        }
        
        $query = "select id from ".Document::TABLE.' where rubrique='.$folder->id;
        
        $return = $this->extractResult($request, $folder->query_liste($query), array(
            "titre" => "document_titre_",
            "chapo" => "document_chapo_",
            "description" => "document_description_"
        ));
        
        return $return;
    }
    
    
}