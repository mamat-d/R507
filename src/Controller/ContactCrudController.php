<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\HttpFoundation\Request;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $markAsTreated = Action::new('markAsTreated', 'Marquer comme traité')
            ->linkToCrudAction('markAsTreated')
            ->displayIf(function (Contact $object) {
                return in_array($object->getStatus(), ['new', 'archived']);
            })
            ->addCssClass('btn btn-primary')
        ;
        $markAsArchived = Action::new('markAsArchived', 'Marquer comme archivé')
            ->linkToCrudAction('markAsArchived')
            ->displayIf(function (Contact $object) {
                return $object->getStatus() == 'treated';
            })
            ->addCssClass('btn btn-primary')
        ;

        return parent::configureActions($actions)
            ->disable(Action::NEW, Action::EDIT, Action::DELETE)
            ->add(Crud::PAGE_DETAIL, $markAsTreated)
            ->add(Crud::PAGE_DETAIL, $markAsArchived);
    }

    public function markAsTreated(Request $request, EntityManagerInterface $em)
    {
        $object = $em->find(Contact::class, $request->query->get('entityId'));

        if (empty($object)) {
            $this->addFlash('danger',"Le contact n'a pas été trouvé");

            return $this->redirectToRoute('admin_contact_index');
        }

        $object->setStatus('treated');
        $em->flush();

        $this->addFlash('success',"Le contact a été marqué comme traité");

        return $this->redirectToRoute('admin_contact_detail', ['entityId' => $object->getId()]);
    }

    public function markAsArchived(Request $request, EntityManagerInterface $em)
    {
        $object = $em->find(Contact::class, $request->query->get('entityId'));

        if (empty($object)) {
            $this->addFlash('danger',"Le contact n'a pas été trouvé");

            return $this->redirectToRoute('admin_contact_index');
        }

        $object->setStatus('archived');
        $em->flush();

        $this->addFlash('success',"Le contact a été marqué comme archivé");

        return $this->redirectToRoute('admin_contact_detail', ['entityId' => $object->getId()]);
    }
}
