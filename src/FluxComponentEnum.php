<?php

declare(strict_types=1);

namespace Juaniquillo\FluxBackendComponents;

enum FluxComponentEnum: string
{
    // general
    case CARD = 'card';
    case HEADING = 'heading';
    case SUBHEADING = 'subheading';
    case TEXT = 'text';
    case BUTTON = 'button';
    case BUTTON_GROUP = 'button.group';
    case BADGE = 'badge';
    case BADGE_CLOSE = 'badge.close';
    case LINK = 'link';
    case ICON = 'icon';
    case SEPARATOR = 'separator';
    case SPACER = 'spacer';
    case ACCENT = 'accent';
    case FLAG = 'flag';
    case PROGRESS = 'progress';

    // layout
    case HEADER = 'header';
    case ASIDE = 'aside';
    case MAIN = 'main';
    case FOOTER = 'footer';
    case CONTAINER = 'container';
    case BRAND = 'brand';
    case PROFILE = 'profile';
    case TOGGLE = 'toggle';

    // avatar
    case AVATAR = 'avatar';
    case AVATAR_GROUP = 'avatar.group';

    // breadcrumbs
    case BREADCRUMBS = 'breadcrumbs';
    case BREADCRUMBS_ITEM = 'breadcrumbs.item';

    // callout
    case CALLOUT = 'callout';
    case CALLOUT_HEADING = 'callout.heading';
    case CALLOUT_LINK = 'callout.link';
    case CALLOUT_TEXT = 'callout.text';

    // forms
    case LABEL = 'label';
    case FIELD = 'field';
    case DESCRIPTION = 'description';
    case ERROR = 'error';
    case TEXT_INPUT = 'input';
    case INPUT_GROUP = 'input.group';
    case INPUT_CLEARABLE = 'input.clearable';
    case INPUT_COPYABLE = 'input.copyable';
    case INPUT_EXPANDABLE = 'input.expandable';
    case INPUT_VIEWABLE = 'input.viewable';
    case TEXT_FILE = 'input.file';
    case TEXTAREA = 'textarea';
    case SELECT = 'select';
    case SELECT_GROUP = 'select.group';
    case OPTION = 'select.option';
    case SWITCH = 'switch';
    case CHECKBOX = 'checkbox';
    case CHECKBOX_GROUP = 'checkbox.group';
    case CHECKBOX_INDICATOR = 'checkbox.indicator';
    case RADIO = 'radio';
    case RADIO_GROUP = 'radio.group';
    case RADIO_INDICATOR = 'radio.indicator';
    case FIELDSET = 'fieldset';
    case LEGEND = 'legend';

    // dropdown
    case DROPDOWN = 'dropdown';
    case MENU = 'menu';
    case MENU_ITEM = 'menu.item';
    case MENU_GROUP = 'menu.group';
    case MENU_HEADING = 'menu.heading';
    case MENU_SEPARATOR = 'menu.separator';
    case MENU_SUBMENU = 'menu.submenu';
    case MENU_CHECKBOX = 'menu.checkbox';
    case MENU_RADIO = 'menu.radio';

    // nav
    case NAVBAR = 'navbar';
    case NAVBAR_ITEM = 'navbar.item';
    case NAVBAR_BADGE = 'navbar.badge';
    case NAVLIST = 'navlist';
    case NAVLIST_GROUP = 'navlist.group';
    case NAVLIST_ITEM = 'navlist.item';
    case NAVLIST_BADGE = 'navlist.badge';
    case NAVMENU = 'navmenu';
    case NAVMENU_ITEM = 'navmenu.item';
    case NAVMENU_SEPARATOR = 'navmenu.separator';
    case SIDEBAR = 'sidebar';
    case SIDEBAR_GROUP = 'sidebar.group';
    case SIDEBAR_ITEM = 'sidebar.item';
    case SIDEBAR_HEADER = 'sidebar.header';
    case SIDEBAR_BRAND = 'sidebar.brand';
    case SIDEBAR_PROFILE = 'sidebar.profile';
    case SIDEBAR_SPACER = 'sidebar.spacer';
    case SIDEBAR_TOGGLE = 'sidebar.toggle';
    case SIDEBAR_COLLAPSE = 'sidebar.collapse';
    case SIDEBAR_NAV = 'sidebar.nav';
    case SIDEBAR_SEARCH = 'sidebar.search';
    case SIDEBAR_BACKDROP = 'sidebar.backdrop';

    // otp
    case OTP = 'otp';
    case OTP_INPUT = 'otp.input';
    case OTP_GROUP = 'otp.group';
    case OTP_SEPARATOR = 'otp.separator';

    // skeleton
    case SKELETON = 'skeleton';
    case SKELETON_GROUP = 'skeleton.group';
    case SKELETON_LINE = 'skeleton.line';

    // tables
    case TABLE = 'table';
    case THEAD = 'table.columns';
    case TH = 'table.column';
    case TBODY = 'table.rows';
    case TR = 'table.row';
    case TD = 'table.cell';

    // modal
    case MODAL = 'modal';
    case MODAL_TRIGGER = 'modal.trigger';
    case MODAL_CLOSE = 'modal.close';

    // toast
    case TOAST = 'toast';
    case TOAST_GROUP = 'toast.group';

    // tooltip
    case TOOLTIP = 'tooltip';
    case TOOLTIP_CONTENT = 'tooltip.content';
}
