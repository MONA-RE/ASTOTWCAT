<?php
/* Copyright (C) 2017  Laurent Destailleur <eldy@users.sourceforge.net>
 * Copyright (C) 2021  Paul LEPONT 		   <paul@kawagency.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * \file        htdocs/custom/moreorderlist/class/actions_moreorderlist.class.php
 * \ingroup     moreorderlist
 * \brief       fichier action/hook moreorderlist
 */

 require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';
 require_once DOL_DOCUMENT_ROOT.'/projet/class/task.class.php';

class ActionsAddSpentTimeOnTaskWhenCloseAgendaTask // extends CommonObject
{




	/**
	 * Overloading the addMoreMassActions function : replacing the parent's function with the one below
	 *
	 * @param   array           $parameters     Hook metadatas (context, etc...)
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	function addMoreMassActions($parameters)
	{

		// [TODO] : il faut ajouter une close pour que le hook ne s'execute que sur la liste des taches de l'agenda.
		// pour le test j'ai ajouté dans le context hook la liste des commandes 'orderlist' et le code ci dessous 
		// s'execute aussi sur la liste des commandes
		global $conf, $user, $langs;
		
		echo 'AAAAAA';

		$error = 0; // Error counter

		// 25%
			$code = 'astotwcat_25';
			$label = img_picto('', 'bill', 'class="pictofixedwidth"').$langs->trans("astotwcat_25");
			$disabled = 0;
			$this->resprints.= '<option value="'.$code.'"'.($disabled ? ' disabled="disabled"' : '').' data-html="'.dol_escape_htmltag($label).'">'.$label.'</option>';
		
		// 50 % 
			$code = 'astotwcat_50';
			$label = img_picto('', 'bill', 'class="pictofixedwidth"').$langs->trans("astotwcat_50");
			$disabled = 0;
			$this->resprints.= '<option value="'.$code.'"'.($disabled ? ' disabled="disabled"' : '').' data-html="'.dol_escape_htmltag($label).'">'.$label.'</option>';
		

		// 75 % 
			$code = 'astotwcat_75';
			$label = img_picto('', 'bill', 'class="pictofixedwidth"').$langs->trans("astotwcat_75");
			$disabled = 0;
			$this->resprints.= '<option value="'.$code.'"'.($disabled ? ' disabled="disabled"' : '').' data-html="'.dol_escape_htmltag($label).'">'.$label.'</option>';
		

		// 100 % 
			$code = 'astotwcat_100';
			$label = img_picto('', 'bill', 'class="pictofixedwidth"').$langs->trans("astotwcat_100");
			$disabled = 0;
			$this->resprints.= '<option value="'.$code.'"'.($disabled ? ' disabled="disabled"' : '').' data-html="'.dol_escape_htmltag($label).'">'.$label.'</option>';
		



		if (!$error) {
			$this->results = array('myreturn' => 999);
			//$this->resprints = 'A text to show';
			return 0; // or return 1 to replace standard code
		} else {
			$this->errors[] = 'Error message';
			return -1;
		}
	}




	/**
	 * Overloading the doMassActions function : replacing the parent's function with the one below
	 *
	 * @param   array           $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          $action         Current action (if set). Generally create or edit or null
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	function doActions ($parameters, &$object, $action)
	{

		global $db, $user, $langs;

		$massaction = GETPOST('massaction');
		$toselect = GETPOST('toselect', 'array');
		$percentAgendaTask =0;
		$percentProjectTask =0;
		$mo_ActionComm = new ActionComm($db);
		$mo_Task = new Task($db);
		$error=0;

		echo 'AAAAAA'.'<br>';
		echo 'ACTION = '.$action.'<br>';
		echo 'massaction = '.$massaction.'<br>';


		// définition des valeurs des pourcentages d'avancement
		switch ($massaction) {
			case 'astotwcat_25':
				$percentAgendaTask = 100;
				$percentProjectTask =25;
				break;
			case 'astotwcat_50':
				$percentAgendaTask = 100;
				$percentProjectTask =50;
				break;
			case 'astotwcat_75':
				$percentAgendaTask = 100;
				$percentProjectTask =75;
				break;
			case 'astotwcat_100':
				$percentAgendaTask = 100;
				$percentProjectTask =100;
				break;
			default:
				// code à exécuter si $variable ne vaut ni 'valeur1' ni 'valeur2'
				break;
		}





// parcours des lignes pour cloture et insérer les temps consommeés pour la tache liée
	
		foreach ($toselect as $toselectid) {

			$mo_ActionComm->fetch($toselectid);
			$result = $mo_ActionComm->updatePercent($toselectid, $percentAgendaTask);
			


			echo 'BBBBB'.'<br>';
			echo 'object->id = '.$mo_ActionComm->id.'<br>';
			echo 'object->ref = '.$mo_ActionComm->ref.'<br>';
			echo 'elementtype = '.$mo_ActionComm->elementtype.'<br>';
			echo 'elementid = '.$mo_ActionComm->elementid.'<br>';
			echo 'label = '.$mo_ActionComm->label.'<br>';
			
			echo 'datep = '.$mo_ActionComm->datep.'<br>';
			echo 'datep format date heure = '.date('d', $mo_ActionComm->datep).'/'.  date('m', $mo_ActionComm->datep).'/'. date('Y', $mo_ActionComm->datep).' '.date('H', $mo_ActionComm->datep).':'.date('i', $mo_ActionComm->datep).'<br>';

			echo 'datef = '.$mo_ActionComm->datef.'<br>';
			echo 'datef format date heure = '.date('d', $mo_ActionComm->datef).'/'.  date('m', $mo_ActionComm->datef).'/'. date('Y', $mo_ActionComm->datef).' '.date('H', $mo_ActionComm->datef).':'.date('i', $mo_ActionComm->datef).'<br>';



			if ($mo_ActionComm->elementtype == 'task') {
				$mo_Task->fetch($mo_ActionComm->elementid);

				$timespent_durationhour = date('H', $mo_ActionComm->datef)-date('H', $mo_ActionComm->datep);
				$timespent_durationmin = date('i', $mo_ActionComm->datef)-date('i', $mo_ActionComm->datep);
				$timespent_note = $mo_ActionComm->label;
				$task_progress = $percentProjectTask;



				if (empty($timespent_durationhour) && empty($timespent_durationmin)) {
					setEventMessages($langs->trans('ErrorFieldRequired', $langs->transnoentitiesnoconv("Duration")), null, 'errors');
					$error++;
				}
			
			
			
				if (!$error) {
					$mo_Task->fetch_projet();
			
					if (empty($mo_Task->project->statut)) {
						setEventMessages($langs->trans("ProjectMustBeValidatedFirst"), null, 'errors');
						$action = 'createtime';
						$error++;
					} else {
						$mo_Task->timespent_note = $timespent_note;

						$mo_Task->progress = $task_progress;
						
						$mo_Task->timespent_duration = $timespent_durationhour  * 60 * 60; // We store duration in seconds
						$mo_Task->timespent_duration += $timespent_durationmin * 60; // We store duration in seconds
						$mo_Task->timespent_withhour = 1; //  hour was entered in date field
						$mo_Task->timespent_date = $mo_ActionComm->datep ;
							
						
						$mo_Task->timespent_fk_user = $user->id;
						$result = $mo_Task->addTimeSpent($user);
						if ($result >= 0) {
							setEventMessages($langs->trans("RecordSaved"), null, 'mesgs');
						} else {
							setEventMessages($langs->trans($mo_Task->error), null, 'errors');
							$error++;
						}
					}
				}




			}
			
		
			if ($result < 0) {
				dol_print_error($db);
				break;
			}
		}
		


		if (0) {

			//var_dump(GETPOST('toselect', 'array'));

			$mo_listeCommande = GETPOST('toselect', 'array');


			foreach ($mo_listeCommande as $key => $value) {

				$mo_commande = new Commande($db);
				$mo_commande->fetch($value);
				print 'key = '.$key.' value = '.$value .' ';
				print $mo_commande->ref.'"'.count($mo_commande->lines) .' ligne(s)"<br>';

				// on boucle sur les lignes de la commande
				foreach ($mo_commande->lines as $key_lines => $value_line) {		
				
					print 'lines = '.$value_line->desc.'<br>';
				
				}

			}






		}
		

		return 0;
	}
	
	
	
}
