<?php

Gatuf::loadFunction('Gatuf_Shortcuts_RenderToResponse');
Gatuf::loadFunction('Gatuf_HTTP_URL_urlForView');

class Encuesta_Views {
	public function index ($request, $match) {
		if (!$request->user->isAnonymous ()) {
			/* Redirigir a la vista de encuestas */
			$url = Gatuf_HTTP_URL_urlForView ('Encuesta_Views_Cuestionario::index');
			return new Gatuf_HTTP_Response_Redirect ($url);
		}
		
		if (!empty($request->REQUEST['_redirect_after'])) {
			$success_url = $request->REQUEST['_redirect_after'];
		} else {
			$success_url = Gatuf::config('encuesta_base').Gatuf::config ('login_success_url', '/');
		}
		
		$error = '';
		if ($request->method == 'POST') {
			foreach (Gatuf::config ('auth_backends', array ('Gatuf_Auth_ModelBackend')) as $backend) {
				$user = call_user_func (array ($backend, 'authenticate'), $request->POST);
				if ($user !== false) {
					break;
				}
			}
			
			if (false === $user) {
				$error = 'El usuario o contraseña no son válidos. El usuario y la contraseña son sensibles a las mayúsculas';
			} else {
				if (!$request->session->getTestCookie ()) {
					$error = 'Necesitas habilitar las cookies para acceder a este sitio';
				} else {
					$request->user = $user;
					$request->session->clear ();
					$request->session->setData('login_time', gmdate('Y-m-d H:i:s'));
					$request->session->deleteTestCookie ();
					return new Gatuf_HTTP_Response_Redirect ($success_url);
				}
			}
		}
		
		/* Mostrar el formulario de login */
		$request->session->createTestCookie ();
		$context = new Gatuf_Template_Context_Request ($request, array ('page_title' => 'Ingresar',
		'_redirect_after' => $success_url,
		'error' => $error));
		$tmpl = new Gatuf_Template ('encuesta/login_form.html');
		return new Gatuf_HTTP_Response ($tmpl->render ($context));
	}
	
	function logout ($request, $match) {
		$success_url = Gatuf::config ('after_logout_page', '/');
		$user_model = Gatuf::config('gatuf_custom_user','Gatuf_User');
		
		$request->user = new $user_model ();
		$request->session->clear ();
		$request->session->setData ('logout_time', gmdate('Y-m-d H:i:s'));
		if (0 !== strpos ($success_url, 'http')) {
			$murl = new Gatuf_HTTP_URL ();
			$success_url = Gatuf::config('encuesta_base').$murl->generate($success_url);
		}
		
		return new Gatuf_HTTP_Response_Redirect ($success_url);
	}
}
