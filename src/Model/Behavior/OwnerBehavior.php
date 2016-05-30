<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * Owner behavior
 */
class OwnerBehavior extends Behavior
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = ['ownerField' => 'owner_id', 'ownerProperty' => 'current_user_owner_id'];

    /*
     * Validations du proprietaire des donnees
     * En beforeFilter le controlleur doit donner le current_user_owner_id au TableRegistry
     *
     */
    public function beforeFind($event, $query, $options)
     {
       // Parametrise le champ en BD
       $owner_field = $this->config()["ownerField"];
       $owner_property = $this->config()["ownerProperty"];

       if(isset($event->subject()->{$owner_property}))
        {
          $query = $query->where([$query->repository()->registryAlias().".".$owner_field => $event->subject()->{$owner_property}]);
        }
     }

     public function beforeSave($event, $entity, $options)
      {
        // Parametrise le champ en BD
        $owner_field = $this->config()["ownerField"];
        $owner_property = $this->config()["ownerProperty"];

        // Obtenir accès à l'objet "Table" qui a appelé le Behavior: $event->subject()
        if(isset($event->subject()->{$owner_property}))
        {

          if($entity->isNew())
          {

            $entity[$owner_field] = $event->subject()->{$owner_property};
          }
          else
          {
            if($entity[$owner_field] != $event->subject()->{$owner_property})
            {
              // Stop la suppression
              $event->stopPropagation();
              // Retourne une erreur
              return false;
            }
          }
        }
      }
}
