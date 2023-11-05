<?php

namespace Module\Admin\Controller;

use Engine\Language;
use Engine\Server;
use Engine\Theme;

class Page extends AdminController
{
	public function getAll()
	{
		$this->view->setData('pages', $this->model->getPages());

		$this->view->render('page/all');
	}

	public function getCategory()
	{
		if (!isset($this->route['parameter']['id'])) {
			$this->view->error('404');
		}

		$this->view->setData('is_category', true);
		$this->view->setData('pages', $this->model->getPagesByCategory($this->route['parameter']['id']));

		$this->view->render('page/all');
	}

	public function getAdd()
	{
		$this->view->setData('authors', $this->model->getAuthors());
		$this->view->setData('categories', $this->model->getCategories());
		$this->view->setData('templates', Theme::pageTemplates());

		$this->view->render('page/add');
	}

	public function getEdit()
	{
		$page_id = $this->route['parameter']['id'];

		$is_translation = false;

		if (isset($this->route['parameter']['language'])) {
			$is_translation = true;

			$data['page_origin'] = $this->model->getPage($page_id);

			if (empty($data['page_origin'])) {
				$this->view->error('404');
			}
		}

		$data['page'] = $this->model->getPage($page_id, $is_translation ? $this->route['parameter']['language'] : null);

		if (empty($data['page'])) {
			$this->view->error('404');
		}

		$data['is_translation'] = $is_translation;

		$data['page']->categories = $this->model->getPageCategories($page_id);
		// TODO
		// $data['page']->custom_fields = $this->model->getPageCustomFields($page_id, $is_translation ? $this->route['parameter']['language'] : null);
		// $data['page']->custom_fieldsets = $this->model->getPageCustomFieldSets($data['page']);

		$data['authors'] = $this->model->getAuthors();
		$data['categories'] = $this->model->getCategories($page_id);
		$data['templates'] = Theme::pageTemplates();

		$this->view->setData($data);
		$this->view->render('page/edit');
	}

	public function getAddTranslation()
	{
		$page_id = $this->route['parameter']['id'];
		$translation_language = $this->route['parameter']['language'];

		if (!Language::has($translation_language)) {
			Server::redirect(site('url_language') . '/admin/page');
		}

		$page = $this->model->getPage($page_id);

		if (empty($page)) {
			Server::redirect(site('url_language') . '/admin/page');
		}

		$translation = [
			'page_id' => $page_id,
			'language' => $translation_language,
			'title' => $page->title,
			'content' => $page->content,
			'excerpt' => $page->excerpt,
			'image' => $page->image,
			'seo_description' => $page->seo_description,
			'seo_keywords' => $page->seo_keywords,
			'seo_image' => $page->seo_image
		];

		if ($this->model->createTranslation('page_translation', $translation)) {
			Server::redirect(site('url_language') . '/admin/page/edit/' . $page_id . '/translation/edit/' . $translation_language);
		} else {
			Server::redirect(site('url_language') . '/admin/page');
		}
	}
}
