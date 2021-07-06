<?php
 
namespace Front\Providers;

use Plenty\Modules\Webshop\Template\Providers\TemplateServiceProvider;
use Plenty\Modules\Webshop\ItemSearch\Helpers\ResultFieldTemplate;
use IO\Helper\TemplateContainer;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Plugin\ServiceProvider;
use Plenty\Plugin\Templates\Twig;


class FrontServiceProvider extends TemplateServiceProvider
{
	/**
    * Register the service provider.
    */
	public function register()
	{

	}
	
	public function boot(Twig $twig, Dispatcher $eventDispatcher)
    {   		
	    $this->overrideTemplate("Ceres::PageDesign.Macros.IncludeComponents", "Front::PageDesign.Macros.IncludeComponents");
		$this->overrideTemplate("Ceres::PageDesign.Macros.ItemName", "Front::PageDesign.Macros.ItemName");
		$this->overrideTemplate("Ceres::PageDesign.Macros.LayoutContainer", "Front::PageDesign.Macros.LayoutContainer");
		$this->overrideTemplate("Ceres::PageDesign.Macros.Translations", "Front::PageDesign.Macros.Translations");
		$this->overrideTemplate("Ceres::PageDesign.Partials.Head", "Front::PageDesign.Partials.Head");
		$this->overrideTemplate("Ceres::PageDesign.Partials.Footer", "Front::PageDesign.Partials.Footer");
		$this->overrideTemplate("Ceres::PageDesign.Partials.PageMetadata", "Front::PageDesign.Partials.PageMetadata");
		$this->overrideTemplate("Ceres::PageDesign.Partials.Header.Header", "Front::PageDesign.Partials.Header.Header");
                $this->overrideTemplate("Ceres::PageDesign.Partials.PaginationTemplate", "Front::PageDesign.Partials.PaginationTemplate");
		$this->overrideTemplate("Ceres::PageDesign.Partials.Header.Breadcrumb", "Front::PageDesign.Partials.Header.Breadcrumb");
		$this->overrideTemplate("Ceres::PageDesign.Partials.Header.DefaultHeader.twig", "Front::PageDesign.Partials.Header.DefaultHeader");
		$this->overrideTemplate("Ceres::PageDesign.Partials.Header.Navigation", "Front::PageDesign.Partials.Header.Navigation");
		$this->overrideTemplate("Ceres::PageDesign.Partials.Header.NavigationList", "Front::PageDesign.Partials.Header.NavigationList");
		$this->overrideTemplate("Ceres::PageDesign.Partials.Header.SideNavigation", "Front::PageDesign.Partials.Header.SideNavigation");
		$this->overrideTemplate("Ceres::PageDesign.PageDesign", "Front::PageDesign.PageDesign");	
	
		$this->overrideTemplate("Ceres::Category.Macros.CategoryTree", "Front::Category.Macros.CategoryTree");
		$this->overrideTemplate("Ceres::Category.Item.Partials.CategoryParallaxImage", "Front::Category.Item.Partials.CategoryParallaxImage");
		$this->overrideTemplate("Ceres::Category.Item.Partials.Pagination", "Front::Category.Item.Partials.Pagination");
		$this->overrideTemplate("Ceres::Category.Content.CategoryContent", "Front::Category.Content.CategoryContent");
		$this->overrideTemplate("Ceres::Category.Item.CategoryItem", "Front::Category.Item.CategoryItem");
		
		$this->overrideTemplate("Ceres::Item.SingleItemWrapper", "Front::Item.SingleItemWrapper");
		$this->overrideTemplate("Ceres::Item.SingleItemView", "Front::Item.SingleItemView");
		
		$this->overrideTemplate("Ceres::StaticPages.CancellationForm", "Front::StaticPages.CancellationForm");
		$this->overrideTemplate("Ceres::StaticPages.CancellationRights", "Front::StaticPages.CancellationRights");
		$this->overrideTemplate("Ceres::StaticPages.ItemNotFound", "Front::StaticPages.ItemNotFound");
		$this->overrideTemplate("Ceres::StaticPages.LegalDisclosure", "Front::StaticPages.LegalDisclosure");
		$this->overrideTemplate("Ceres::StaticPages.PageNotFound", "Front::StaticPages.PageNotFound");
		$this->overrideTemplate("Ceres::StaticPages.PrivacyPolicy", "Front::StaticPages.PrivacyPolicy");
		$this->overrideTemplate("Ceres::StaticPages.TermsAndConditions", "Front::StaticPages.TermsAndConditions");
		
		// $this->overrideTemplate("Ceres::Widgets.Header.TopBarWidget", "Front::Widgets.Header.CustomTopBarWidget");	
		// $this->overrideTemplate("Ceres::Widgets.Item.OrderPropertyWidget", "Front::Widgets.Item.CustomOrderPropertyWidget");	

		$container = pluginApp(ResultFieldTemplate::class);
                $container->setTemplate(ResultFieldTemplate::TEMPLATE_LIST_ITEM,'Front::ResultFields.ListItem');
    }
}
