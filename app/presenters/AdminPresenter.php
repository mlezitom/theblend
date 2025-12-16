<?php

namespace App\Presenters;


use Nette\Utils\Html;
use Ublaboo\DataGrid\DataGrid;

class AdminPresenter extends BasePresenter
{
    protected function startup()
    {
        if (!$this->user->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
        $this->setLayout('admin');
        parent::startup();
    }

    public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}

	public function createComponentEventsGrid()
    {
        $grid = new DataGrid();
        $grid->setDataSource($this->eventsModel->getGridDatasource());

        $grid->addColumnNumber('id', 'ID');
        $grid->addColumnDateTime('event_date', 'Datum')
            ->setRenderer(function($row) {
                return $row['event_date']->format('j.n.Y');
            });
        ;
        $grid->addColumnText('venue_name', 'Název baru');
        $grid->addColumnText('city', 'Město');
        $grid->addColumnLink('link_goout', 'GoOut')
            ->setRenderer(function($row) {
                return Html::el('a')
                    ->setAttribute('href', $row['link_goout'])
                    ->setAttribute('target', '_blank')
                    ->setHtml(Html::el('img')->addAttributes([
                        'src' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACoAAAAqCAYAAADFw8lbAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA/VJREFUeNrUmV1sDFEUx7cltBEtWh9VWkqreKgHIg0PxIOSRuiXj0QbrFUvJEJCRNIElXhAvNBGLZoID2iJ9R2NkIjEUykhQkJ8BVF0syjrf7JnmmvNzL13OjuNk/xy0+69M/89c+65Z84mRaNR3/9g/Z0ubGxsTMNQDErAVJAFhoBUnvIddIJ34DG4Bm6D9+J1AoFAYoRCYBGGarAEjJdMHw0mgzmgFrwFreAkuJUQj0JgDobNwC94TddGgXXkSHAa1IN2lYVJKjEKkSRuFxjpcuiFwR6wGyEQdSwUAgdi2As2aNz8DigEGRprzsTCNfDJakKyjchUjiUdkbRhykG3pmdpTQj3zNISigUpLLJM42YhsAh8AwMchMFM2mi49wgdj+4HizVFVoIIoC/pNDnPACcgtp9UKCZVcipRtRssMsx/9/YEmQ+22G4miMzEcB/kKF70CljKid2wYeApj06tiw4TbK52K49u0xB5GSyLE+mWDaKUZfro4c1sDKsVL3QBVOAbf07g8b4QmorNPOrns1pml8iTENmV4DqENtTGv4RCOaWTFYobh0SGPSqaSnjf9Hg0H0yQLGqjPAmRXzys7tLBLFHoPHa1XZ4s9dCToi0QhU6XeLLKg5i0skmi0FybmCztQ5FGadgjdIzF4y7vY5FkaWLhnG6SJ53u7iRO2G5ZiihUPJ/pCC2DyG6HF/7Bp9bgXqwXs1BUFEonTKaQqqq4zHNiX3Uqr/iXO+TNXA47wyJijL6Mi4lmLKj2OhhxzwIuvqcI/+4Uhb4wKf+CWLjWQ5HT+HjOj/vojSj0rkVR3YALrPFAJPUFWkCeyccPRaFtFu85tIOP4EI1CRRZyI8716YI6hH6HHTYXK8pQZ4lT17kLouZfTCedjLvvJ8YjklKLvLsKhdFGp7Ms5nTatS8Yj16nFsudnbUJc+SJ6/beNLoXR34p3Dml/8GhZs09DJmC/jky5bMOwtND6zemfaBRwqVd9Bhni3kmJQ11z6CHbYtHQiYy48lWXKx32A9LVEUWcQpaJzCXD+82WT7Xo8JNzHsVLgYrT0MVA4FSubnFEU2x4u07JRgYh3tcsVKieK6WlL4tig8bqN3VavVJOMFqmKDoMZCZMgmmYt2lTouVqWltD+KmN2Ooc4nb/r+8sWatE1CCjovyZOGHQKbIDJi6Q3FRi716Q+aFAxmthzc88X69VmSudTP3wqBQeljU/1VBGLTuSFA/dIMSaKmV+rhNnPCHC71EPla6bVB9+cb7uVXgJVcN6r2Qik0noFTvlj/vsOscHZNqCCYEv9EMNsX+xmHUs9YMJQ3KRW8r7jWpdcbSntP2OOWFb7rQr22PwIMAEKWM1nnFeRyAAAAAElFTkSuQmCC',
                        'style' => 'width: 25px',
                    ])
                );
            }
        );
        
        $grid->addColumnStatus('soldout', 'Vyprodáno')
            /*->setCaret(FALSE)*/
        ->addOption(1, 'Ano')
            /*->setIcon('check')*/
            ->setClass('btn-danger')
            ->endOption()
        ->addOption(0, 'Ne')
            /*->setIcon('user')*/
            ->setClass('btn-success')
            ->endOption()
        ->onChange[] = [$this, 'soldoutChange'];

        $grid->addColumnStatus('visible', 'Viditelné na webu')
            /*->setCaret(FALSE)*/
        ->addOption(1, 'Ano')
            /*->setIcon('check')*/
            ->setClass('btn-success')
            ->endOption()
        ->addOption(0, 'Ne')
            /*->setIcon('user')*/
            ->setClass('btn-danger')
            ->endOption()
        ->onChange[] = [$this, 'statusChange'];

        /**
         * Some action
         */
        /*$grid->addAction('delete', '', 'delete!')
            ->setIcon('trash')
            ->setTitle('Smazat')
            ->setClass('btn btn-xs btn-danger ajax')
            ->setConfirm('Opravdu smazat event ve městě %s?', 'city')
        ;

        $grid->addInlineAdd()
            ->setPositionTop()
            ->onControlAdd[] = function($container) {
            $container->addText('id', '')->setAttribute('readonly');
            $container->addText('event_date', '')->setAttribute('class', 'calendar');
            $container->addText('bar_name', '');
            $container->addText('city', '');
            $container->addText('link_goout', 'https://goout.com/');
            $container->addCheckbox('soldout', '');
            $container->addCheckbox('visible', '');
        };

        $grid->getInlineAdd()->onSubmit[] = function($values) {
            /**
             * Save new values
             * /
            $this->eventsModel->getTable()->insert([
                'bar_name' => $values['bar_name'],
                'city' => $values['city'],
                'event_date' => $values['event_date'],
                'link_goout' => $values['link_goout'],
                'soldout' => $values['soldout'],
                'visible' => $values['visible'],
            ]);

            $this->flashMessage("Event přidán.", 'success');

            $this->redrawControl('flashes');
            $this['eventsGrid']->redrawControl();
        };

        $grid->addInlineEdit()
            ->onControlAdd[] = function($container) {
            $booleans = [0 => 'Ne', 1 => 'Ano'];
            $container->addText('id', '')->setAttribute('readonly');
            $container->addText('event_date', '');
            $container->addText('bar_name', '');
            $container->addText('city', '');
            $container->addText('link_goout', 'https://goout.com/');
            $container->addSelect('soldout', null, $booleans);
            $container->addSelect('visible', '', $booleans);
        };

        $grid->getInlineEdit()->onSetDefaults[] = function($container, $item) {
            $container->setDefaults([
                'id' => $item->id,
                'bar_name' => $item->bar_name,
                'event_date' => $item->event_date->format('Y-m-d'),
                'link_goout' => $item->link_goout,
                'city' => $item->city,
//                'soldout' => $item->soldout,
//                'visible' => $item->visible,
            ]);
        };

        $grid->getInlineEdit()->onSubmit[] = function($id, $values) {
            $this->eventsModel->getTable()->get($id)->update([
                'bar_name' => $values['bar_name'],
                'city' => $values['city'],
                'event_date' => $values['event_date'],
                'link_goout' => $values['link_goout'],
//                'soldout' => $values['soldout'],
//                'visible' => $values['visible'],
            ]);
        };

        */

        return $grid;
    }

    /**
     * @param int $id
     */
//    public function handleDelete($id)
//    {
//        $this->eventsModel->get($id)->delete();
//
//        $this->flashMessage("Event smazán", 'info');
//
//        if ($this->isAjax()) {
//            $this->redrawControl('flashes');
//            $this['eventsGrid']->reload();
//        } else {
//            $this->redirect('this');
//        }
//    }

    public function statusChange($id, $new_status)
    {
        if (in_array($new_status, [0, 1])) {
            $this->eventsModel->get($id)
                ->update(['visible' => $new_status]);
        }

        if($new_status) {
            $this->flashMessage('Event zobrazen na webu.');
        } else {
            $this->flashMessage('Event skryt na webu.');
        }
        $this->redirect('this');
//        if ($this->isAjax()) {
//            $this->redrawControl('flashes');
//            $this['eventsGrid']->redrawItem($id);
//        }
    }


    public function soldoutChange($id, $new_status)
    {
        if (in_array($new_status, [0, 1])) {
            $this->eventsModel->get($id)
                ->update(['soldout' => $new_status]);
        }

        if($new_status) {
            $this->flashMessage('Event nastaven jako vyprodaný.');
        } else {
            $this->flashMessage('Event nastaven jako dostupný.');
        }
        $this->redirect('this');
//        if ($this->isAjax()) {
//            $this->redrawControl('flashes');
//            $this['eventsGrid']->redrawItem($id);
//        }
    }
}
