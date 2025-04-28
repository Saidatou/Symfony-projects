<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Controller\Admin\PostCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{

    public function __construct(private AdminUrlGenerator $adminUrlGenerator){}

    // private function __construct(private AdminUrlGenerator $adminUrlGenerator){}
    #[Route('%app.admin_path%', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        // Option 1. You can make your dashboard redirect to some common page of your backend
        
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->redirect($this->adminUrlGenerator->setController(PostCrudController::class)->generateUrl());
        
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Bloggy');
    }

    public function configureMenuItems(): iterable
    {
        return [
        MenuItem::section(),
        MenuItem::linkToUrl('Visit public website', 'fa fa-home', '/'),
        MenuItem::section('Blog'),
        MenuItem::linkToCrud('Posts', 'fas fa-file-text', Post::class),
        MenuItem::linkToCrud('Comments', 'fas fa-comment', Comment::class),
        MenuItem::linkToCrud('Tags', 'fa fa-tag', Tag::class),
        MenuItem::section('User'),
        MenuItem::linkToCrud('Users', 'fas fa-user', User::class),

        ];
    }
}
