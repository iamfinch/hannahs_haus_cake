<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\View\JsonView;
use Cake\View\XmlView;
use Exception;
/**
 * States Controller
 *
 * @property \App\Model\Table\StatesTable $States
 * @method \App\Model\Entity\State[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StatesController extends AppController
{
    public function viewClasses(): array
    {
        return [JsonView::class, XmlView::class];
    }

    public function beforeFilter(\Cake\Event\EventInterface $event) {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(["getStates"]);
    }

    public function getStates() {
        $this->viewBuilder()->setLayout("ajax");
        try {
            $this->autorender = false;
            if (!$this->request->is('ajax')) {
                throw new Exception();
            }

            $countryId = (int) $this->request->getQuery("country_id");
            $this->set("countryId", $countryId);
            $this->set("isEmpty", empty($countryId));
            if (empty($countryId)) {
                throw new Exception();
            }

            $foundStates = $this->States->getStates($countryId);
            $this->set("foundStates", json_encode($foundStates));
            $content = '<option value="0">(Please select a state/province)</option>';
            $length = count($foundStates);
            $this->set("length", $length);
            for ($i = 0; $i < $length; $i++) {
                $id = $foundStates[$i]["id"];
                $name = $foundStates[$i]["name"];
                $content .= "<option value='" . $id . "'> " . $name . "</option>";
            }
            $this->set("content", $content);
            return $this->response->withStringBody($content);
        } catch (Exception $e) {
            return $this->response->withStringBody("<p>There was an issue</p>");
        }
    }
}
