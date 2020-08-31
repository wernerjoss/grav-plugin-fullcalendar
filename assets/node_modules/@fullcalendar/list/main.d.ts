import { ViewApi, MountArg, DateComponent, ViewProps, createElement, Seg, DateMarker, EventStore, EventUiHash, DateRange, EventRenderRange, Identity, ClassNamesGenerator, CustomContentGenerator, DidMountHandler, WillUnmountHandler, FormatterInput, DateFormatter, PluginDef } from '@fullcalendar/common';

interface NoEventsContentArg {
    text: string;
    view: ViewApi;
}
declare type NoEventsMountArg = MountArg<NoEventsContentArg>;
declare class ListView extends DateComponent<ViewProps> {
    private computeDateVars;
    private eventStoreToSegs;
    render(): createElement.JSX.Element;
    setRootEl: (rootEl: HTMLDivElement | null) => void;
    renderEmptyMessage(): createElement.JSX.Element;
    renderSegList(allSegs: Seg[], dayDates: DateMarker[]): createElement.JSX.Element;
    _eventStoreToSegs(eventStore: EventStore, eventUiBases: EventUiHash, dayRanges: DateRange[]): Seg[];
    eventRangesToSegs(eventRanges: EventRenderRange[], dayRanges: DateRange[]): any[];
    eventRangeToSegs(eventRange: EventRenderRange, dayRanges: DateRange[]): any[];
}


declare const OPTION_REFINERS: {
    listDayFormat: typeof createFalsableFormatter;
    listDaySideFormat: typeof createFalsableFormatter;
    noEventsClassNames: Identity<ClassNamesGenerator<NoEventsContentArg>>;
    noEventsContent: Identity<CustomContentGenerator<NoEventsContentArg>>;
    noEventsDidMount: Identity<DidMountHandler<MountArg<NoEventsContentArg>>>;
    noEventsWillUnmount: Identity<WillUnmountHandler<MountArg<NoEventsContentArg>>>;
};
declare function createFalsableFormatter(input: FormatterInput | false): DateFormatter;

declare type ExtraOptionRefiners = typeof OPTION_REFINERS;
declare module '@fullcalendar/common' {
    interface BaseOptionRefiners extends ExtraOptionRefiners {
    }
}

declare const _default: PluginDef;

export default _default;
export { ListView, NoEventsContentArg, NoEventsMountArg };
