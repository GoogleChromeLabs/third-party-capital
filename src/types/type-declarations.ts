/* eslint-disable no-unused-vars */
type ScriptStrategy = 'server' | 'client' | 'idle' | 'worker';
type ScriptLocation = 'head' | 'body';
type ScriptAction = 'append' | 'prepend';
export type SrcVal = {
  url: string;
  slugParam?: string;
  params?: Array<string>;
};

export type AttributeVal = string | null | SrcVal | boolean | undefined;

export type HtmlAttributes = {
  src?: SrcVal;
  [key: string]: AttributeVal;
};

type ScriptBase = {
  params?: Array<string>;
  optionalParams?: Record<string, string | number | undefined | null>;
  strategy: ScriptStrategy;
  location: ScriptLocation;
  action: ScriptAction;
  key?: string;
};

export type ExternalScript = ScriptBase & {
  url: string;
};

export type CodeBlock = ScriptBase & {
  code: string;
};

export type Script = ExternalScript | CodeBlock;
export type Scripts = Script[];

export interface Data {
  id: string;
  description: string;
  website?: string;
  html?: {
    element: string;
    attributes: HtmlAttributes;
  };
  stylesheets?: Array<string>;
  scripts?: Scripts;
}

export interface Inputs {
  [key: string]: any;
}

export interface Output {
  id: string;
  description: string;
  website?: string;
  html?: string;
  stylesheets?: Array<string>;
  scripts?: Scripts;
}

/* Google Analytics */
export interface GoogleAnalyticsParams {
  id: string;
  /**
   * The name of the dataLayer object. Defaults to 'dataLayer'.
   */
  l?: string;
  /**
   * Consent type for Google Analytics.
   * @default 'default'
   */
  consentType?: string;
  /**
   * Consent values for Google Analytics.
   */
  consentValues?: { [key: string]: string };
}

export interface GTag {
  (fn: 'js', opt: Date): void;
  (fn: 'config', opt: string): void;
  (fn: 'event', opt: string, opt2?: { [key: string]: any }): void;
  (fn: 'set', opt: { [key: string]: string }): void;
  (fn: 'get', opt: string): void;
  (fn: 'consent', opt: 'default', opt2: { [key: string]: string }): void;
  (fn: 'consent', opt: 'update', opt2: { [key: string]: string }): void;
  (fn: 'config', opt: 'reset'): void;
}

export type DataLayer = Array<Parameters<GTag> | Record<string, unknown>>;

/* Google Tag Manager */
export interface GoogleTagManagerParams {
  id: string;
  /**
   * The name of the dataLayer object. Defaults to 'dataLayer'.
   */
  l?: string;
  /**
   * Consent type for Google Tag Manager.
   * @default 'default'
   */
  consentType?: string;
  /**
   * Consent values for Google Tag Manager.
   */
  consentValues?: { [key: string]: string };
}

interface GoogleTagManagerDataLayerApi {
  name: 'dataLayer';
  set: (opt: { [key: string]: string }) => void;
  get: (key: string) => void;
  reset: () => void;
}

type GoogleTagManagerDataLayerStatus = {
  dataLayer: {
    gtmDom: boolean;
    gtmLoad: boolean;
    subscribers: number;
  };
};

export type GoogleTagManagerInstance = GoogleTagManagerDataLayerStatus & {
  [key: string]: {
    callback: () => void;
    dataLayer: GoogleTagManagerDataLayerApi;
  };
};

export interface GoogleTagManagerApi {
  google_tag_manager: GoogleTagManagerInstance;
}

/* Google Maps Embed */
export interface GoogleMapsEmbedParams {
  key: string;
  mode: 'place' | 'view' | 'directions' | 'streetview' | 'search';
  q?: string;
  center?: string;
  zoom?: string;
  maptype?: 'roadmap' | 'satellite';
  language?: string;
  region?: string;
}

/* Youtube Embed */
export interface YoutubeEmbedAttributes {
  videoid: string;
  playlabel?: string;
}
