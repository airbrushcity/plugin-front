<template>
    <div class="desktop-navigation open">
        <div v-show="isNavigationInitialized">
            <ul class="breadcrumb d-block px-3 py-0">
                <li class="breadcrumb-item" @click="slideTo(null, true)">
                    <i class="fa fa-home" aria-hidden="true"></i>
                </li>

                <li class="breadcrumb-item" v-for="breadcrumb in breadcrumbs" @click="slideTo(breadcrumb.parent, true)">
                    {{ breadcrumb.name }}
                </li>
            </ul>
            <ul v-menu id="menu-1" class="mainmenu p-0 m-0 menu-active">
                <li class="ddown" v-if="dataContainer1.parent" @click="slideTo(dataContainer1.parent && dataContainer1.parent.parent || null, true)">
                    <span class="nav-direction btn-up">
                        <i class="fa fa-lg fa-level-up" aria-hidden="true"></i>
                    </span>
                </li>

                <li class="ddown" v-for="category in dataContainer1.categories">
                    <a :href="getCategoryUrl(category.url)">{{ category.details[0].name }}</a>
                    <span class="nav-direction" v-if="category.childCount" @click="slideTo(category)">
                        <i class="fa fa-lg fa-caret-right" aria-hidden="true"></i>
                    </span>
                </li>
                <template v-if="dataContainer1.categories[0]">
                    <li class="ddown" v-for="number in dataContainer1.categories[0].siblingCount - dataContainer1.categories.length">
                        <span class="nav-placeholder m-3" :style="{width: (Math.random() * 20 + 60) + '%'}"></span>
                    </li>
                </template>
                <template v-else-if="dataContainer1.parent">
                    <li class="ddown" v-for="number in dataContainer1.parent.childCount">
                        <span class="nav-placeholder m-3" :style="{width: (Math.random() * 20 + 60) + '%'}"></span>
                    </li>
                </template>
            </ul>

            <ul v-menu id="menu-2" class="mainmenu p-0 m-0">
                <li class="ddown" v-if="dataContainer2.parent" @click="slideTo(dataContainer2.parent && dataContainer2.parent.parent || null, true)">
                    <span class="nav-direction btn-up">
                        <i class="fa fa-lg fa-level-up" aria-hidden="true"></i>
                    </span>
                </li>

                <li  class="ddown" v-for="category in dataContainer2.categories">
                    <a :href="getCategoryUrl(category.url)">{{ category.details[0].name }}</a>
                    <span class="nav-direction" v-if="category.childCount" @click="slideTo(category)">
                        <i class="fa fa-lg fa-caret-right" aria-hidden="true"></i>
                    </span>
                </li>
                <template v-if="dataContainer2.categories[0]">
                    <li class="ddown" v-for="number in dataContainer2.categories[0].siblingCount - dataContainer2.categories.length">
                        <span class="nav-placeholder m-3" :style="{width: (Math.random() * 20 + 60) + '%'}"></span>
                    </li>
                </template>
                <template v-else-if="dataContainer2.parent">
                    <li class="ddown" v-for="number in dataContainer2.parent.childCount">
                        <span class="nav-placeholder m-3" :style="{width: (Math.random() * 20 + 60) + '%'}"></span>
                    </li>
                </template>
            </ul>
        </div>

        <template v-if="!isNavigationInitialized">
            <loading-animation></loading-animation>
        </template>
    </div>
</template>

<script>
import { MediaQueryHelper } from "../../helper/MediaQueryHelper";
import { isNullOrUndefined, isDefined } from "../../helper/utils";
import Vue from "vue";
import { mapState } from "vuex";

export default {

    name: "desktop-navigation",

    props: {
        template: {
            default: "#vue-desktop-navigation",
            type: String
        },
        initialCategory: Object,
        breakpoints: {
            type: Array,
            default: () => ["lg", "xl", "xxl"]
        },
        includeLanguage: Boolean
    },

    data()
    {
        return {
            dataContainer1: {
                parent: {},
                categories: []
            },
            dataContainer2: {
                parent: {},
                categories: []
            },
            useFirstContainer: false,
            isNavigationInitialized: true,
            selectedCategory: null
        };
    },

    computed:
    {
        breadcrumbs()
        {
            const breadcrumbs = [];
            let container = this.useFirstContainer ? this.dataContainer2 : this.dataContainer1;

            while (container && container.parent && Object.keys(container.parent).length)
            {
                breadcrumbs.unshift(
                    {
                        name: container.parent.details[0].name,
                        parent: container.parent || null
                    });

                container = container.parent;
            }

            return breadcrumbs;
        },

        ...mapState({
            navigationTree: state => state.navigation.tree
        })
    },

    created()
    {
        this.$store.commit("setCurrentCategory", this.initialCategory);
    },

    mounted()
    {
        this.loadInitialTree();
    },

    methods:
    {

        loadInitialTree()
        {
            if (this.navigationTree.length <= 0)
            {
                const categoryId = this.initialCategory && this.initialCategory.id ? this.initialCategory.id : null;

                this.$store.dispatch("loadPartialNavigationTree", categoryId)
                    .then(response =>
                    {
                        this.$store.commit("setNavigationTree", response);
                        this.initNavigation();
                    });
            }
        },

        initNavigation()
        {
            if (this.initialCategory && this.initialCategory.id)
            {
                if (this.initialCategory.linklist === "N")
                {
                    this.$store.commit("setCurrentCategory", this.initialCategory);
                }
                else
                {
                    this.$store.dispatch("setCurrentCategoryById", { categoryId: parseInt(this.initialCategory.id) });
                    this.initialSlide(this.$store.state.navigation.currentCategory);
                }
            }

            this.dataContainer1.parent = null;
            this.dataContainer1.categories = this.navigationTree;
            this.isNavigationInitialized = true;
        },

        initialSlide(currentCategory)
        {
            if (currentCategory)
            {
                if (currentCategory.children)
                {
                    this.slideTo(currentCategory);
                }
                else if (currentCategory.parent)
                {
                    this.slideTo(currentCategory.parent);
                }
            }
        },

        slideTo(category, back)
        {
            const children = isDefined(category) ? category.children : this.navigationTree;
            const categoryId = isDefined(category) ? category.id : null;

            this.loadPartialTree(categoryId);
            this.selectedCategory = category;

            if (this.useFirstContainer)
            {
                this.dataContainer1.parent = category;
                this.dataContainer1.categories = children || [];

                $("#menu-2").trigger("menu-deactivated", { back: !!back });
                $("#menu-1").trigger("menu-activated", { back: !!back });
            }
            else
            {
                this.dataContainer2.parent = category;
                this.dataContainer2.categories = children || [];

                $("#menu-1").trigger("menu-deactivated", { back: !!back });
                $("#menu-2").trigger("menu-activated", { back: !!back });
            }

            this.useFirstContainer = !this.useFirstContainer;
        },

        loadPartialTree(categoryId)
        {
            // eslint-disable-next-line eqeqeq
            if (this.selectedCategory != categoryId ||
                (isDefined(this.selectedCategory) && this.selectedCategory.id !== categoryId))
            {
                this.$store.dispatch("loadPartialNavigationTree", categoryId)
                    .then(response =>
                    {
                        if ((isNullOrUndefined(this.selectedCategory) && isNullOrUndefined(categoryId)) ||
                            (isDefined(this.selectedCategory) && this.selectedCategory.id === categoryId))
                        {
                            this.$store.commit("setNavigationTree", response);
                            this.updateDataContainers(categoryId);
                        }
                    });
            }
        },

        updateDataContainers(categoryId)
        {
            const containers = ["dataContainer1", "dataContainer2"];
            const category = this.getCategoryById(categoryId, this.navigationTree);

            for (const container of containers)
            {
                if (category)
                {
                    this[container].parent = category;
                    this[container].categories = category.children;
                }
                else
                {
                    // root level
                    this[container].parent = null;
                    this[container].categories = this.navigationTree;
                }
            }
        },

        getCategoryById(categoryId, tree)
        {
            for (const cat of tree)
            {
                if (categoryId === cat.id)
                {
                    return cat;
                }
                else if (cat.children)
                {
                    const foundCat = this.getCategoryById(categoryId, cat.children);

                    if (foundCat)
                    {
                        return foundCat;
                    }
                }
            }

            return null;
        },

        getCategoryUrl(url)
        {
            const trailingSlash = url[0] === "/" ? "" : "/";
            const prefix = this.includeLanguage ? `/${App.language}${trailingSlash}` : "";

            return prefix + url;
        }
    }
    
}
</script>
