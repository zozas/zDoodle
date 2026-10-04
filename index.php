<?php
	ini_set('display_errors', 'On');
	error_reporting(E_ALL | E_STRICT);
	session_start();
	require_once('include.php');
	// Load configuration
	$config = new ini;
	$config->open('config.ini.php');
	$config->read();
	// Load language
	$language = new ini;
	$language->open($config->get('ENCODING', 'LANGUAGE'));
	$language->read();
	// Initialize session and log
	$session = new session;
	// Load template
	$template_main = new template;
	$template_main->open('index.tpl');
	$template_main->set('meta_title', $config->get('APPLICATION', 'TITLE'));
	$template_main->set('meta_viewport', $config->get('APPLICATION', 'VIEWPORT'));
	$template_main->set('meta_charset', $config->get('ENCODING', 'CHARSET'));
	$template_main->set('meta_author', $config->get('APPLICATION', 'AUTHOR'));
	$template_main->set('meta_contact', $config->get('APPLICATION', 'CONTACT'));
	$template_main->set('meta_distribution', $config->get('APPLICATION', 'DISTRIBUTION'));
	$template_main->set('meta_google', $config->get('APPLICATION', 'GOOGLE'));
	$template_main->set('meta_product', $config->get('APPLICATION', 'PRODUCT'));
	$template_main->set('meta_robots', $config->get('APPLICATION', 'ROBOTS'));
	$template_main->set('meta_xua', $config->get('APPLICATION', 'XUA'));
	$template_main->set('meta_type', $config->get('APPLICATION', 'TYPE'));
	$template_main->set('meta_version', $config->get('APPLICATION', 'VERSION'));
	$template_main->set('meta_copyright', $config->get('APPLICATION', 'COPYRIGHT'));
	$template_main->set('meta_description', $config->get('APPLICATION', 'DESCRIPTION'));
	$template_main->set('meta_disclaimer', $config->get('APPLICATION', 'DISCLAIMER'));
	$template_main->set('meta_keywords', $config->get('APPLICATION', 'KEYWORDS'));
	$template_main->set('link_home', $language->get('STRING', 'HOME'));
	$template_main->set('link_about', $language->get('STRING', 'ABOUT'));
	$template_main->set('link_admin', $language->get('STRING', 'ADMIN'));
	$template_main->set('link_help', $language->get('STRING', 'HELP'));
	// Initialize actions
	$action = '';
	if (isset($_POST['action']))
		$action = $_POST['action'];
	else
		if (isset($_GET['action']))
			$action = $_GET['action'];
	// Interpret actions
	// About
	if ($action=='about') {
		$template_main->set('game_content', $language->get('STRING', 'ABOUT'));
		$template_about = new template;
		$template_about->open('about.tpl');
		$template_about->set('title', $config->get('APPLICATION', 'TITLE'));
		$template_about->set('version', $config->get('APPLICATION', 'VERSION'));
		$template_about->set('app_version', $language->get('STRING', 'VERSION'));
		$template_about->set('author', $config->get('APPLICATION', 'AUTHOR'));
		$template_about->set('contact', $config->get('APPLICATION', 'CONTACT'));
		$template_about->set('copyright', $config->get('APPLICATION', 'COPYRIGHT'));
		$template_about->set('description', $config->get('APPLICATION', 'DESCRIPTION'));
		$template_about->set('disclaimer', $config->get('APPLICATION', 'DISCLAIMER'));
		$template_about->set('databases', $language->get('STRING', 'DATABASES'));
		$template_about->set('total_databases', count(glob($config->get('APPLICATION', 'DATABASES').'/*')));
		$template_main->set('game_options', $template_about->get());
		$template_main->set('game_progress', '');
	// Admin 
	} else if ($action=='admin') {
		$template_main->set('game_content', $language->get('STRING', 'ADMIN'));
		$template_admin = new template;
		$template_admin->open('admin.tpl');
		$template_admin->set('create', $language->get('STRING', 'CREATE'));
		$template_admin->set('download', $language->get('STRING', 'DOWNLOAD'));
		$template_admin->set('update', $language->get('STRING', 'UPDATE'));
		$template_admin->set('delete', $language->get('STRING', 'DELETE'));
		$template_admin->set('results', $language->get('STRING', 'RESULTS'));
		$template_admin->set('generate', $language->get('STRING', 'GENERATE'));
		$template_admin->set('template', $language->get('STRING', 'TEMPLATE'));
		$template_main->set('game_options', $template_admin->get());
		$template_main->set('game_progress', '');
	// Quiz generate
	} else if ($action=='admin_generate') {
		$template_main->set('game_content', $language->get('STRING', 'ADMIN'));
		$template_admin = new template;
		$template_admin->open('generate.tpl');
		$template_admin->set('date_list', $language->get('STRING', 'DATE_LIST'));
		$template_admin->set('question_add', $language->get('STRING', 'DATE_ADD'));
		$template_admin->set('generate', $language->get('STRING', 'GENERATE'));
		$template_admin->set('pin_length', $config->get('APPLICATION', 'PIN_LENGTH'));
		$template_admin->set('pin', $language->get('STRING', 'PIN_QUIZ'));
		$template_admin->set('adminpin', $language->get('STRING', 'PIN_QUIZ_ADMIN'));
		$template_admin->set('version', $language->get('STRING', 'VERSION'));
		$template_admin->set('title', $language->get('STRING', 'TITLE'));	
		$template_admin->set('language', $language->get('STRING', 'LANGUAGE'));
		$template_admin->set('instructions', $language->get('STRING', 'INSTRUCTIONS'));
		$template_admin->set('minutes', $language->get('STRING', 'MINUTES'));
		$template_admin->set('duration', $language->get('STRING', 'DURATION'));
		$template_admin->set('author', $language->get('STRING', 'AUTHOR'));
		$template_admin->set('doodledata', $language->get('STRING', 'DOODLE_DATA'));
		$template_main->set('game_options', $template_admin->get());
		$template_main->set('game_progress', '');
	// Quiz generate file
	} else if ($action=='admin_generate_file') {
		if (!empty($_POST)) {
			$generator = new ConfigGenerator($_POST);
			$content =$generator->generateContent();

			if (ob_get_level()) {
				ob_end_clean();
			}
			header('Content-Type: text/plain; charset=utf-8');
			header('Content-Disposition: attachment; filename='.$_POST['pin'].'.'.$config->get('APPLICATION', 'DATABASE_EXTENSION'));
			header('Content-Length: '.strlen($content));
			header('Pragma: no-cache');
			header('Expires: 0');
			echo $content;
			exit;
		} else {
			$session->erase_session();
			$template_redirect = new template;
			$template_redirect->open('redirect.tpl');
			$template_redirect->set('url', '?');
			$template_main->set('game_content', '');
			$template_main->set('game_options', '');
			$template_main->set('game_progress', $template_redirect->get());
		}
	// Doodle results form
	} else if ($action=='admin_results') {
		$template_main->set('game_content', $language->get('STRING', 'ADMIN'));
		$template_admin = new template;
		$template_admin->open('results.tpl');
		$template_admin->set('pin_length', $config->get('APPLICATION', 'PIN_LENGTH'));
		$template_admin->set('pin_name', $language->get('STRING', 'PIN_QUIZ'));
		$template_admin->set('admin_pin_name', $language->get('STRING', 'PIN_QUIZ_ADMIN'));
		$template_admin->set('submit', $language->get('STRING', 'RESULTS'));
		$template_admin->set('pin-action', 'admin_results_quiz');
		$template_main->set('game_options', $template_admin->get());
		$template_main->set('game_progress', '');
	// Doodle results
	} else if ($action=='admin_results_quiz') {
		$pin = '';
		if (isset($_POST['pin']))
			$pin = $_POST['pin'];
		else
			if (isset($_GET['pin']))
				$pin = $_GET['pin'];
		$admin_pin = '';
		if (isset($_POST['admin_pin']))
			$admin_pin = $_POST['admin_pin'];
		else
			if (isset($_GET['admin_pin']))
				$admin_pin = $_GET['admin_pin'];
		$result = '';
		if ($pin === "" || $admin_pin === "") {
			$result = $language->get('STRING', 'ERROR_PIN_EMPTY');
		} else {
			$filepath = $config->get('APPLICATION', 'DATABASES').'/'.$pin.'.'.$config->get('APPLICATION', 'DATABASE_EXTENSION');
			if (!file_exists($filepath)) {
				$result = $language->get('STRING', 'ERROR_FILE_MISSING');
			} else {
				$content = file_get_contents($filepath);
				if (!$content) {
					$result = $language->get('STRING', 'ERROR_FILE_READ');
				} else {
					$lines = explode("\n", $content);
					if (count($lines) < 5) {
						$result = $language->get('STRING', 'ERROR_FILE_STRUCTURE');
					} else {
						$iniLines = array_slice($lines, 3, count($lines) - 5);
						$iniContent = implode("\n", $iniLines);
						$ini = parse_ini_string($iniContent, true, INI_SCANNER_RAW);
						if (!$ini || !isset($ini["DATABASE"])) {
							$result = $language->get('STRING', 'ERROR_FILE_STRUCTURE');
						} else {
							if (!isset($ini["DATABASE"]["ADMIN"])) {
								$result = $language->get('STRING', 'ERROR_FILE_STRUCTURE');
							} else {
								$db_admin = trim($ini["DATABASE"]["ADMIN"]);
								if ($db_admin !== $admin_pin) {
									$result = $language->get('STRING', 'ERROR_PIN');
								} else {
									$resultspath = $config->get('APPLICATION', 'RESULTS').'/'.$pin.'.'.$config->get('APPLICATION', 'DATABASE_EXTENSION');
									if (!file_exists($resultspath)) {
										$result = $language->get('STRING', 'ERROR_FILE_MISSING');
									} else {
										echo "\xEF\xBB\xBF";
										$headers = [$language->get('STRING', 'PARTICIPANT'), $language->get('STRING', 'PIN_QUIZ'), $language->get('STRING', 'DATE'), $language->get('STRING', 'IP'), $language->get('STRING', 'DATES')];
										$lines = file($resultspath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
										if (!$lines) {
											$result = $language->get('STRING', 'ERROR_FILE_MISSING');
										} else {
											$fp = fopen('php://memory', 'w');
											fputcsv($fp, $headers, ',', '"', '\\');
											foreach ($lines as $line) {
												$parts = explode(',', $line);
												$parts = array_map('trim', $parts);
												fputcsv($fp, $parts);
											}
											rewind($fp);
											header('Content-Type: text/csv');
											header('Content-Disposition: attachment; filename="data_export.csv"');
											header('Pragma: no-cache');
											header('Expires: 0');
											fpassthru($fp);
											$dateCounts = [];
											if (($handle = fopen($resultspath, 'r')) !== FALSE) {
											$isHeader = true;
												while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
													if ($isHeader) {
														$isHeader = false;
														continue;
													}
													$selectedDates = array_slice($data, 4);
													foreach ($selectedDates as $date) {
														$date = trim($date);
														if (!empty($date)) {
															if (!isset($dateCounts[$date])) {
																$dateCounts[$date] = 0;
															}
															$dateCounts[$date]++;
														}
													}
												}
												fclose($handle);
												arsort($dateCounts);
												$template_admin = new template;
												$template_admin->open('poll.tpl');
												$votes_per_day = "";
												foreach ($dateCounts as $date => $count) {													
													$template_votes = new template;
													$template_votes->open('polldate.tpl');
													$doodle_date_option = new DateTime($date, new DateTimeZone('Europe/Athens'));
													$doodle_date_option_end = clone $doodle_date_option;
													$doodle_date_option_end->modify('+30 minutes');
													$template_votes->set('time_start', $doodle_date_option->format('H:i'));
													$template_votes->set('time_end', $doodle_date_option_end->format('H:i'));
													$template_votes->set('votes', $count);
													$template_votes->set('day_name', $language->get('DAYS', $doodle_date_option->format('l')));
													$template_votes->set('day_number', $doodle_date_option->format('d'));
													$template_votes->set('month', $doodle_date_option->format('m'));
													$template_votes->set('year', $doodle_date_option->format('Y'));
													$votes_per_day = $votes_per_day.$template_votes->get();
												}
												$template_admin->set('doodle_list', $votes_per_day);
												$result = $template_admin->get();
											} else {
												$result = $language->get('STRING', 'ERROR_FILE_MISSING');
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		$template_main->set('game_content', $language->get('STRING', 'ADMIN'));
		$template_admin = new template;
		$template_main->set('game_options', $language->get('STRING', 'RESULTS'));
		$template_main->set('game_progress', $result);
	// Download doodle template
	} else if ($action=='admin_template') {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($config->get('TEMPLATES', 'DATABASE')).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: '.filesize($config->get('TEMPLATES', 'DATABASE')));
		readfile($config->get('TEMPLATES', 'DATABASE'));
	// Doodle create
	} else if ($action=='admin_create') {
		$template_main->set('game_content', $language->get('STRING', 'ADMIN'));
		$template_admin = new template;
		$template_admin->open('create.tpl');
		$template_admin->set('create', $language->get('STRING', 'CREATE'));
		$template_main->set('game_options', $template_admin->get());
		$template_main->set('game_progress', '');
	// Doodle create / upload
	} else if ($action=='admin_create_upload') {
		$result = '';
		if (!isset($_FILES['quiz_upload']) || $_FILES['quiz_upload']['error'] !== UPLOAD_ERR_OK) {
			$result = $language->get('STRING', 'ERROR_FILE_READ');
		} else {
			$tmp = $_FILES['quiz_upload']['tmp_name'];
			$content = file_get_contents($tmp);
			if (!$content) {
				$result = $language->get('STRING', 'ERROR_FILE_READ');
			} else {
				$lines = explode("\n", $content);
				if (count($lines) < 5) {
					$result = $language->get('STRING', 'ERROR_FILE_STRUCTURE');
				} else {
					$iniLines = array_slice($lines, 3, count($lines) - 5);
					$iniContent = implode("\n", $iniLines);
					$ini = parse_ini_string($iniContent, true, INI_SCANNER_RAW);
					if (!$ini || !isset($ini["DATABASE"])) {
						$result = $language->get('STRING', 'ERROR_FILE_STRUCTURE');
					} else {
						if (!isset($ini["DATABASE"]["PIN"])) {
							$result = $language->get('STRING', 'ERROR_PIN_MISSING');
						} else {
							$PIN = trim($ini["DATABASE"]["PIN"]);
							if ($PIN === "") {
								$result = $language->get('STRING', 'ERROR_PIN_EMPTY');
							} else {
								$filename = $PIN . ".ini.php";
								$savePath = $config->get('APPLICATION', 'DATABASES').'/'.$filename;
								if (file_exists($savePath)) {
									$result = $language->get('STRING', 'ERROR_PIN_EXISTS');
								} else {
									if (!move_uploaded_file($tmp, $savePath)) {
										$result = $language->get('STRING', 'ERROR_FILE_SAVE');
									} else {
										$result = $language->get('STRING', 'FILE_UPLOAD');
									}
								}
							}
						}
					}
				}
			}
		}
		$template_main->set('game_content', $language->get('STRING', 'ADMIN'));
		$template_admin = new template;
		$template_main->set('game_options', $language->get('STRING', 'CREATE'));
		$template_main->set('game_progress', $result);
	// Doodle download
	} else if ($action=='admin_download') {
		$template_main->set('game_content', $language->get('STRING', 'ADMIN'));
		$template_admin = new template;
		$template_admin->open('download.tpl');
		$template_admin->set('pin_length', $config->get('APPLICATION', 'PIN_LENGTH'));
		$template_admin->set('pin_name', $language->get('STRING', 'PIN_QUIZ'));
		$template_admin->set('admin_pin_name', $language->get('STRING', 'PIN_QUIZ_ADMIN'));
		$template_admin->set('submit', $language->get('STRING', 'DOWNLOAD'));
		$template_admin->set('pin-action', 'admin_download_quiz');
		$template_main->set('game_options', $template_admin->get());
		$template_main->set('game_progress', '');
	// Doodle download file
	} else if ($action=='admin_download_quiz') {
		$pin = '';
		if (isset($_POST['pin']))
			$pin = $_POST['pin'];
		else
			if (isset($_GET['pin']))
				$pin = $_GET['pin'];
		$admin_pin = '';
		if (isset($_POST['admin_pin']))
			$admin_pin = $_POST['admin_pin'];
		else
			if (isset($_GET['admin_pin']))
				$admin_pin = $_GET['admin_pin'];
		$result = '';
		if ($pin === "" || $admin_pin === "") {
			$result = $language->get('STRING', 'ERROR_PIN_EMPTY');
		} else {
			$filepath = $config->get('APPLICATION', 'DATABASES').'/'.$pin.'.'.$config->get('APPLICATION', 'DATABASE_EXTENSION');
			if (!file_exists($filepath)) {
				$result = $language->get('STRING', 'ERROR_FILE_MISSING');
			} else {
				$content = file_get_contents($filepath);
				if (!$content) {
					$result = $language->get('STRING', 'ERROR_FILE_READ');
				} else {
					$lines = explode("\n", $content);
					if (count($lines) < 5) {
						$result = $language->get('STRING', 'ERROR_FILE_STRUCTURE');
					} else {
						$iniLines = array_slice($lines, 3, count($lines) - 5);
						$iniContent = implode("\n", $iniLines);
						$ini = parse_ini_string($iniContent, true, INI_SCANNER_RAW);
						if (!$ini || !isset($ini["DATABASE"])) {
							$result = $language->get('STRING', 'ERROR_FILE_STRUCTURE');
						} else {
							if (!isset($ini["DATABASE"]["ADMIN"])) {
								$result = $language->get('STRING', 'ERROR_FILE_STRUCTURE');
							} else {
								$db_admin = trim($ini["DATABASE"]["ADMIN"]);
								if ($db_admin !== $admin_pin) {
									$result = $language->get('STRING', 'ERROR_PIN');
								} else {
									if (!file_exists($filepath)) {
										$result = $language->get('STRING', 'ERROR_FILE_MISSING');
									} else {
										$resultspath = $config->get('APPLICATION', 'DATABASES').'/'.$pin.'.'.$config->get('APPLICATION', 'DATABASE_EXTENSION');
										if (!file_exists($resultspath)) {
											$result = $language->get('STRING', 'ERROR_FILE_MISSING');
										} else {
											header('Content-Description: File Transfer');
											header('Content-Type: application/octet-stream');
											header('Content-Disposition: attachment; filename="'.basename($resultspath).'"');
											header('Expires: 0');
											header('Cache-Control: must-revalidate');
											header('Pragma: public');
											header('Content-Length: '.filesize($resultspath));
											readfile($resultspath);
										}
									}
								}
							}
						}
					}
				}
			}
		}
		$template_main->set('game_content', $language->get('STRING', 'ADMIN'));
		$template_admin = new template;
		$template_main->set('game_options', $language->get('STRING', 'DOWNLOAD'));
		$template_main->set('game_progress', $result);
	// Doodle update form
	} else if ($action=='admin_update') {
		$template_main->set('game_content', $language->get('STRING', 'ADMIN'));
		$template_admin = new template;
		$template_admin->open('update.tpl');
		$template_admin->set('pin_length', $config->get('APPLICATION', 'PIN_LENGTH'));
		$template_admin->set('pin_name', $language->get('STRING', 'PIN_QUIZ'));
		$template_admin->set('admin_pin_name', $language->get('STRING', 'PIN_QUIZ_ADMIN'));
		$template_admin->set('submit', $language->get('STRING', 'UPDATE'));
		$template_admin->set('pin-action', 'admin_update_quiz');
		$template_main->set('game_options', $template_admin->get());
		$template_main->set('game_progress', '');
	// Doodle update
	} else if ($action=='admin_update_quiz') {
		$pin = '';
		if (isset($_POST['pin']))
			$pin = $_POST['pin'];
		else
			if (isset($_GET['pin']))
				$pin = $_GET['pin'];
		$admin_pin = '';
		if (isset($_POST['admin_pin']))
			$admin_pin = $_POST['admin_pin'];
		else
			if (isset($_GET['admin_pin']))
				$admin_pin = $_GET['admin_pin'];
		$result = '';
		if ($pin === "" || $admin_pin === "") {
			$result = $language->get('STRING', 'ERROR_PIN_EMPTY');
		} else {
			$filepath = $config->get('APPLICATION', 'DATABASES').'/'.$pin.'.'.$config->get('APPLICATION', 'DATABASE_EXTENSION');
			if (!file_exists($filepath)) {
				$result = $language->get('STRING', 'ERROR_FILE_MISSING');
			} else {
				$content = file_get_contents($filepath);
				if (!$content) {
					$result = $language->get('STRING', 'ERROR_FILE_READ');
				} else {
					$lines = explode("\n", $content);
					if (count($lines) < 5) {
						$result = $language->get('STRING', 'ERROR_FILE_STRUCTURE');
					} else {
						$iniLines = array_slice($lines, 3, count($lines) - 5);
						$iniContent = implode("\n", $iniLines);
						$ini = parse_ini_string($iniContent, true, INI_SCANNER_RAW);
						if (!$ini || !isset($ini["DATABASE"])) {
							$result = $language->get('STRING', 'ERROR_FILE_STRUCTURE');
						} else {
							if (!isset($ini["DATABASE"]["ADMIN"])) {
								$result = $language->get('STRING', 'ERROR_FILE_STRUCTURE');
							} else {
								$db_admin = trim($ini["DATABASE"]["ADMIN"]);
								if ($db_admin !== $admin_pin) {
									$result = $language->get('STRING', 'ERROR_PIN');
								} else {
									if (!isset($_FILES['quiz_upload']) || $_FILES['quiz_upload']['error'] !== UPLOAD_ERR_OK) {
										$result = $language->get('STRING', 'ERROR_FILE_READ');
									} else {
										$tmp = $_FILES['quiz_upload']['tmp_name'];
										$content = file_get_contents($tmp);
										if (!$content) {
											$result = $language->get('STRING', 'ERROR_FILE_READ');
										} else {
											$lines = explode("\n", $content);
											if (count($lines) < 5) {
												$result = $language->get('STRING', 'ERROR_FILE_STRUCTURE');
											} else {
												$iniLines = array_slice($lines, 3, count($lines) - 5);
												$iniContent = implode("\n", $iniLines);
												$ini = parse_ini_string($iniContent, true, INI_SCANNER_RAW);
												if (!$ini || !isset($ini["DATABASE"])) {
													$result = $language->get('STRING', 'ERROR_FILE_STRUCTURE');
												} else {
													if (!isset($ini["DATABASE"]["PIN"])) {
														$result = $language->get('STRING', 'ERROR_PIN_MISSING');
													} else {
														$new_pin = trim($ini["DATABASE"]["PIN"]);
														if ($new_pin === "") {
															$result = $language->get('STRING', 'ERROR_PIN_EMPTY');
														} else {
															$filename = $new_pin . ".ini.php";
															$savePath = $config->get('APPLICATION', 'DATABASES').'/'.$filename;
															if ($new_pin != $pin) {
																$result = $language->get('STRING', 'ERROR_PIN_DIFFERENT');
															} else {
																if (!unlink($savePath)) {
																	$result = $language->get('STRING', 'ERROR_FILE_DELETE');
																} else {
																	if (!move_uploaded_file($tmp, $savePath)) {
																		$result = $language->get('STRING', 'ERROR_FILE_SAVE');
																	} else {
																		$result = $language->get('STRING', 'FILE_UPDATE');
																	}
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		$template_main->set('game_content', $language->get('STRING', 'ADMIN'));
		$template_admin = new template;
		$template_main->set('game_options', $language->get('STRING', 'UPDATE'));
		$template_main->set('game_progress', $result);
	// Delete doodle form
	} else if ($action=='admin_delete') {
		$template_main->set('game_content', $language->get('STRING', 'ADMIN'));
		$template_admin = new template;
		$template_admin->open('delete.tpl');
		$template_admin->set('pin_length', $config->get('APPLICATION', 'PIN_LENGTH'));
		$template_admin->set('pin_name', $language->get('STRING', 'PIN_QUIZ'));
		$template_admin->set('admin_pin_name', $language->get('STRING', 'PIN_QUIZ_ADMIN'));
		$template_admin->set('submit', $language->get('STRING', 'DELETE'));
		$template_admin->set('pin-action', 'admin_delete_quiz');
		$template_main->set('game_options', $template_admin->get());
		$template_main->set('game_progress', '');
	// Delete doodle
	} else if ($action=='admin_delete_quiz') {
		$pin = '';
		if (isset($_POST['pin']))
			$pin = $_POST['pin'];
		else
			if (isset($_GET['pin']))
				$pin = $_GET['pin'];
		$admin_pin = '';
		if (isset($_POST['admin_pin']))
			$admin_pin = $_POST['admin_pin'];
		else
			if (isset($_GET['admin_pin']))
				$admin_pin = $_GET['admin_pin'];
		$result = '';
		if ($pin === "" || $admin_pin === "") {
			$result = $language->get('STRING', 'ERROR_PIN_EMPTY');
		} else {
			$filepath = $config->get('APPLICATION', 'DATABASES').'/'.$pin.'.'.$config->get('APPLICATION', 'DATABASE_EXTENSION');
			if (!file_exists($filepath)) {
				$result = $language->get('STRING', 'ERROR_FILE_MISSING');
			} else {
				$content = file_get_contents($filepath);
				if (!$content) {
					$result = $language->get('STRING', 'ERROR_FILE_READ');
				} else {
					$lines = explode("\n", $content);
					if (count($lines) < 5) {
						$result = $language->get('STRING', 'ERROR_FILE_STRUCTURE');
					} else {
						$iniLines = array_slice($lines, 3, count($lines) - 5);
						$iniContent = implode("\n", $iniLines);
						$ini = parse_ini_string($iniContent, true, INI_SCANNER_RAW);
						if (!$ini || !isset($ini["DATABASE"])) {
							$result = $language->get('STRING', 'ERROR_FILE_STRUCTURE');
						} else {
							if (!isset($ini["DATABASE"]["ADMIN"])) {
								$result = $language->get('STRING', 'ERROR_FILE_STRUCTURE');
							} else {
								$db_admin = trim($ini["DATABASE"]["ADMIN"]);
								if ($db_admin !== $admin_pin) {
									$result = $language->get('STRING', 'ERROR_PIN');
								} else {
									if (!unlink($filepath)) {
										$result = $language->get('STRING', 'ERROR_FILE_DELETE');
									} else {
										$resultspath = $config->get('APPLICATION', 'RESULTS').'/'.$pin.'.'.$config->get('APPLICATION', 'DATABASE_EXTENSION');
										if (!unlink($resultspath)) {
											$result = $language->get('STRING', 'ERROR_FILE_DELETE');
										} else {
											$result = $language->get('STRING', 'FILE_DELETED');
										}
									}
								}
							}
						}
					}
				}
			}
		}
		$template_main->set('game_content', $language->get('STRING', 'ADMIN'));
		$template_admin = new template;
		$template_main->set('game_options', $language->get('STRING', 'DELETE'));
		$template_main->set('game_progress', $result);
	// Finalize voting
	} else if ($action=='vote') {
		$votes = '';
		if (isset($_POST['vote']))
			$votes = $_POST['vote'];
		else
			if (isset($_GET['vote']))
				$votes = $_GET['vote'];
		$participant = '';
		if (isset($_POST['participant']))
			$participant = $_POST['participant'];
		else
			if (isset($_GET['participant']))
				$participant = $_GET['participant'];
		if($session->exist('PIN')) {
			$database_file = $config->get('APPLICATION', 'DATABASES').'/'.$session->get('PIN').'.'.$config->get('APPLICATION', 'DATABASE_EXTENSION');
			if (file_exists($database_file)) {
				if (!empty($votes)) {
					$database = new ini;
					$database->open($database_file);
					$database->read();
					if ($participant=='') {
						$participant = $language->get('STRING', 'UNKNOWN');
					}
					$resultspath = $config->get('APPLICATION', 'RESULTS').'/'.$session->get('PIN').'.'.$config->get('APPLICATION', 'DATABASE_EXTENSION');
					file_put_contents($resultspath, $participant.','.$database->get('DATABASE', 'PIN').','.date('Y-m-d H:i:s').','.$session->ip().','.implode(',', $votes).PHP_EOL, FILE_APPEND);
				}
				$result = "";
				$dateCounts = [];
				if (($handle = fopen($resultspath, 'r')) !== FALSE) {
					$isHeader = true;
					while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
						if ($isHeader) {
							$isHeader = false;
							continue;
						}
						$selectedDates = array_slice($data, 4);
						foreach ($selectedDates as $date) {
							$date = trim($date);
							if (!empty($date)) {
								if (!isset($dateCounts[$date])) {
									$dateCounts[$date] = 0;
								}
								$dateCounts[$date]++;
							}
						}
					}
					fclose($handle);
					arsort($dateCounts);
					$template_admin = new template;
					$template_admin->open('poll.tpl');
					$votes_per_day = "";
					foreach ($dateCounts as $date => $count) {													
						$template_votes = new template;
						$template_votes->open('polldate.tpl');
						$doodle_date_option = new DateTime($date, new DateTimeZone('Europe/Athens'));
						$doodle_date_option_end = clone $doodle_date_option;
						$doodle_date_option_end->modify('+30 minutes');
						$template_votes->set('time_start', $doodle_date_option->format('H:i'));
						$template_votes->set('time_end', $doodle_date_option_end->format('H:i'));
						$template_votes->set('votes', $count);
						$template_votes->set('day_name', $language->get('DAYS', $doodle_date_option->format('l')));
						$template_votes->set('day_number', $doodle_date_option->format('d'));
						$template_votes->set('month', $doodle_date_option->format('m'));
						$template_votes->set('year', $doodle_date_option->format('Y'));
						$votes_per_day = $votes_per_day.$template_votes->get();
					}
					$template_admin->set('doodle_list', $votes_per_day);
					$result = $template_admin->get();
				} else {
					$result = $language->get('STRING', 'ERROR_FILE_MISSING');
				}
				$template_main->set('game_content',$database->get('DATABASE', 'TITLE'));
				$template_main->set('game_options', $language->get('STRING', 'RESULTS'));
				$template_main->set('game_progress', $result);
			} else {
				$session->erase_session();
				$template_redirect = new template;
				$template_redirect->open('redirect.tpl');
				$template_redirect->set('url', '?');
				$template_main->set('game_content', '');
				$template_main->set('game_options', '');
				$template_main->set('game_progress', $template_redirect->get());
			}
		} else {
			$session->erase_session();
			$template_redirect = new template;
			$template_redirect->open('redirect.tpl');
			$template_redirect->set('url', '?');
			$template_main->set('game_content', '');
			$template_main->set('game_options', '');
			$template_main->set('game_progress', $template_redirect->get());
		}
	// Verify ID or not before starting
	} else if ($action=='id') {
		$pin = '';
		if (isset($_POST['pin']))
			$pin = $_POST['pin'];
		else
			if (isset($_GET['pin']))
				$pin = $_GET['pin'];
		$database_file = $config->get('APPLICATION', 'DATABASES').'/'.$pin.'.'.$config->get('APPLICATION', 'DATABASE_EXTENSION');
		if (file_exists($database_file)) {
			$database = new ini;
			$database->open($database_file);
			$database->read();
			if ($pin == $database->get('DATABASE', 'PIN')) {
				$session->set('PIN', $pin);
				if ($session->get('PIN') == $database->get('DATABASE', 'PIN')) {
					$template_doodle = new template;
					$template_doodle->open('doodle.tpl');
					$template_doodle->set('submit', $language->get('STRING', 'SUBMIT'));
					$template_doodle->set('participant_name', $language->get('STRING', 'PARTICIPANT'));
					$template_doodle->set('instructions', $database->get('DATABASE', 'INSTRUCTIONS'));
					$template_doodle->set('duration_title', $language->get('STRING', 'DURATION'));
					$template_doodle->set('duration', $database->get('DATABASE', 'DURATION'));
					$template_doodle->set('minutes', $language->get('STRING', 'MINUTES'));
					$template_doodle->set('author', $database->get('DATABASE', 'AUTHOR'));
					$resultspath = $config->get('APPLICATION', 'RESULTS').'/'.$session->get('PIN').'.'.$config->get('APPLICATION', 'DATABASE_EXTENSION');
					$dateCounts = [];
					if (($handle = fopen($resultspath, 'r')) !== FALSE) {
						$isHeader = true;
						while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
							if ($isHeader) {
								$isHeader = false;
								continue;
							}
							$selectedDates = array_slice($data, 4);
							foreach ($selectedDates as $date) {
								$date = trim($date);
								if (!empty($date)) {
									if (!isset($dateCounts[$date])) {
										$dateCounts[$date] = 0;
									}
									$dateCounts[$date]++;
								}
							}
						}
						fclose($handle);
						arsort($dateCounts);
					}
					$doodle_list = "";
					for ($i = 1; $i <= $database->get('DATABASE', 'ENTRIES'); $i++) {
						$doodle_date_option = new DateTime(($database->get('DOODLES', 'D_'.$i)), new DateTimeZone('Europe/Athens'));
						$template_date = new template;
						$template_date->open('date.tpl');
						$doodle_date_option_end = clone $doodle_date_option;
						$doodle_date_option_end->modify('+30 minutes');
						$template_date->set('time_start', $doodle_date_option->format('H:i'));
						$template_date->set('time_end', $doodle_date_option_end->format('H:i'));
						$template_date->set('vote_id', $database->get('DOODLES', 'D_'.$i));
						$template_date->set('day_name', $language->get('DAYS', $doodle_date_option->format('l')));
						$template_date->set('day_number', $doodle_date_option->format('d'));
						$template_date->set('month', $doodle_date_option->format('m'));
						$votes_counted = 0;
						foreach ($dateCounts as $date => $count) {
							$date_voted = new DateTime($date, new DateTimeZone('Europe/Athens'));
							if ($date_voted == $doodle_date_option) {
								$votes_counted = $count;
							}
						}
						$template_date->set('votes', $votes_counted);
						$template_date->set('year', $doodle_date_option->format('Y'));
						$doodle_list = $doodle_list.$template_date->get();
					}
					$template_doodle->set('doodle_list', $doodle_list);
					$template_main->set('game_content', $database->get('DATABASE', 'TITLE'));
					$template_main->set('game_options', '');
					$template_main->set('game_progress', $template_doodle->get());
				} else {
					$session->erase_session();
					$template_redirect = new template;
					$template_redirect->open('redirect.tpl');
					$template_redirect->set('url', '?');
					$template_main->set('game_content', '');
					$template_main->set('game_options', '');
					$template_main->set('game_progress', $template_redirect->get());
				}
			} else {
				$session->erase_session();
				$template_redirect = new template;
				$template_redirect->open('redirect.tpl');
				$template_redirect->set('url', '?');
				$template_main->set('game_content', '');
				$template_main->set('game_options', '');
				$template_main->set('game_progress', $template_redirect->get());
			}
		} else {
			$session->erase_session();
			$template_redirect = new template;
			$template_redirect->open('redirect.tpl');
			$template_redirect->set('url', '?');
			$template_main->set('game_content', '');
			$template_main->set('game_options', '');
			$template_main->set('game_progress', $template_redirect->get());
		}
	// Default start menu
	} else {
		$template_pinpad = new template;
		$template_pinpad->open('pinpad.tpl');
		$template_pinpad->set('pin_length', $config->get('APPLICATION', 'PIN_LENGTH'));
		$template_pinpad->set('pin_name', $language->get('STRING', 'PIN_QUIZ'));
		$template_pinpad->set('pin-action', 'id');
		$template_pinpad->set('pin_variable', 'pin');
		$template_main->set('game_content', $language->get('STRING', 'PIN_QUIZ_HELP'));
		$template_main->set('game_options', '');
		$template_main->set('game_progress', $template_pinpad->get());
	}
	echo $template_main->get();
?>
