<?php

use yii;
use fl\cms\apps\items\pages\menu\main\App;

function createMenu()
{
    $flCmsSession = yii::$app->session['fl_cms'];
    return '<nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                ' . setPagesMenu() . '
                ' . setProjectsMenu() . '
                ' . setElementsMenu() . '
            </ul>
            <ul class="navbar-nav ml-auto">
                ' . setLoginMenu() . '
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>';
}

function setLoginMenu()
{
    if(!Yii::$app->user->isGuest){
        return '<li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">' . '$fullName' . '</span>
                        <div class="dropdown-divider"></div>
                        <a href="/logout" target="_self" class="dropdown-item">
                            <i class="fas fa-door-open mr-2"></i> Logout
                        </a>
                    </div>
                </li>';
    }else{
        return '<li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="/logout" target="_self" class="dropdown-item">
                            <i class="fas fa-door-open mr-2"></i> Login
                        </a>
                    </div>
                </li>';
    }
}

function setPagesMenu()
{
    if(!App::checkAccessMenuPages()){
        return '';
    }
    $dropdownMenuBtns = '';
    if(App::checkAccessActionPageCreate()){
        $dropdownMenuBtns .= '<a href="javascript:void(0);" class="fl-action cms-page-create dropdown-item"
                               data-string__entity="pages"
                               data-string__action_name="create"
                               data-json_to_obj__action=\'["form",["modal"]]\'
                               data-string__form.values.e_parent_cms_page="' . Yii::$app->params['page']['e_cms_page'] .'"
                            >
                                <i class="far fa-circle"> </i> Создать страницу
                            </a>
                            <div class="dropdown-divider"></div>';
    }
    if(App::checkAccessActionPageUpdate()){
        $dropdownMenuBtns .= '<a href="'. App::$params['edit_url'] .'" target="_self" class="dropdown-item">
                                <i class="far fa-circle"> </i> Редактировать страницу
                            </a>
                            <div class="dropdown-divider"></div>';
    }
    if(App::checkAccessActionPageDelete()){
        $dropdownMenuBtns .= '<a href="javascript:void(0);" class="fl-action cms-page-create dropdown-item"
                               data-string__entity="pages"
                               data-string__action_name="delete"
                               data-json_to_obj__action=\'["form",["modal"]]\'
                               data-string__form.values.name="' . Yii::$app->params['page']['data']['cms_page']['name'] .'"
                               data-string__form.values.title="' . Yii::$app->params['page']['data']['cms_page']['title'] .'"
                               data-string__form.values.e_cms_page="' . Yii::$app->params['page']['e_cms_page'] .'"
                            >
                                <i class="far fa-circle"> </i> Удалить страницу
                            </a>
                            <div class="dropdown-divider"></div>';
    }
    if(!$dropdownMenuBtns){
        return '';
    }
    return '<li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    Страницы
                </a>
                <div class="dropdown-menu  dropdown-menu-left">
                    '. $dropdownMenuBtns .'
                </div>
            </li>';
}

function setProjectsMenu()
{
    if(!App::checkAccessMenuProjects()){
        return '';
    }
    return '<li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    Проекты
                </a>
                <div class="dropdown-menu  dropdown-menu-left">
                    <a href="javascript:void(0);" class="fl-action cms-page-create dropdown-item"
                       data-string__entity="projects"
                       data-string__action_name="create"
                       data-json_to_obj__action=\'["form",["modal"]]\'
                       data-string__form.values.e_parent_cms_page="' . Yii::$app->params['page']['e_cms_page'] . '"
                    >
                        <i class="far fa-circle"> </i> Создать проект
                    </a>
                </div>
            </li>';
}

function setElementsMenu()
{
    if(!App::checkAccessMenuElements()){
        return '';
    }
    return '<li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    Элементы
                </a>
                <div class="dropdown-menu  dropdown-menu-left">
                    <a href="' . yii::$app->params['fl_cms']['domains']['main_domain'] . '/entities/classes' . '" target="_self" class="dropdown-item">
                        <!-- Message Start -->
                        <i class="far fa-circle"> </i> Классы элементов
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="' . yii::$app->params['fl_cms']['domains']['main_domain'] . '/entities/config' . '" target="_self" class="dropdown-item">
                        <!-- Message Start -->
                        <i class="far fa-circle"> </i> Конфигурации элементов
                    </a>
                    <div class="dropdown-divider"></div>

                    <a href="' . yii::$app->params['fl_cms']['domains']['main_domain'] . '/entities/instances' . '" target="_self" class="dropdown-item">
                        <!-- Message Start -->
                        <i class="far fa-circle"> </i> Экземпляры элементов
                    </a>
                </div>
            </li>';
}

return createMenu();