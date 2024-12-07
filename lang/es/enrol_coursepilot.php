<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     enrol_coursepilot
 * @category    string
 * @copyright   2024 Diego Monroy <dfelipe.monroyc@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// General.
$string['pluginname'] = 'Enrol Course Pilot';
$string['unenrolled'] = 'Desinscrito';
$string['enrolled'] = 'Inscrito';

// Configuraciones.
$string['setting_configpage'] = 'Configuración de Course Pilot';
$string['setting_enable'] = 'Habilitar Course Pilot';
$string['setting_enable_desc'] = 'Habilita o Deshabilitar el plugin Enrol Course Pilot.';
$string['setting_template_categories'] = 'Categorías de Plantilla';
$string['setting_template_categories_desc'] = 'Estas categorías se usarán para listar los cursos plantilla al copiar.';
$string['setting_formation_categories'] = 'Categorías de Formación';
$string['setting_formation_categories_desc'] = 'Estas categorías se usarán para validar cuando los usuarios están inscritos en cursos de formación.';

// API.
$string['api_plugin_disabled'] = 'El plugin Course Pilot está deshabilitado.';
$string['api_invalid_courseid'] = 'El curso plantilla {$a} no existe o la categoría no está configurada como plantilla.';
$string['api_invalid_userid'] = 'El usuario {$a} no existe.';
$string['api_invalid_formationid'] = 'La categoría del curso de formación {$a} no existe o no está configurada como formación.';
$string['api_course_was_not_copied'] = 'No se pudo copiar el curso.';
$string['api_no_permission'] = 'No tienes permiso para realizar esta acción.';
$string['api_course_copy_queued'] = 'La copia del curso ha sido puesta en cola y se creará en breve.';
$string['api_enrollment_updated'] = 'El usuario {$a->username} ha sido {$a->action} exitosamente en el curso {$a->courseid}.';
$string['api_no_enrolment_method'] = 'Ocurrió un error al intentar inscribir al usuario en el curso {$a}.';
$string['api_user_already_enrolled'] = 'El usuario ya está inscrito en el curso {$a}.';
$string['api_user_already_unenrolled'] = 'El usuario actualmente no está inscrito en el curso {$a}.';
$string['api_user_not_enrolled'] = 'El usuario no está inscrito en el curso {$a}.';
$string['api_invalid_parameters'] = 'Parámetros inválidos, por favor verifica los valores e intenta nuevamente.';
$string['api_invalid_roleid_parameter'] = 'Parámetro roleid inválido, por favor verifica el valor e intenta nuevamente.';
