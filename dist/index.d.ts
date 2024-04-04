type ScriptStrategy = 'server' | 'client' | 'idle' | 'worker';
type ScriptLocation = 'head' | 'body';
type ScriptAction = 'append' | 'prepend';
type SrcVal = {
  url: string;
  slugParam?: string;
  params?: Array<string>;
};
type AttributeVal = string | null | SrcVal | boolean | undefined;
type HtmlAttributes = {
  src?: SrcVal;
  [key: string]: AttributeVal;
};
type ScriptBase = {
  params?: Array<string>;
  strategy: ScriptStrategy;
  location: ScriptLocation;
  action: ScriptAction;
  key?: string;
};
type ExternalScript = ScriptBase & {
  url: string;
};
type CodeBlock = ScriptBase & {
  code: string;
};
type Script = ExternalScript | CodeBlock;
type Scripts = Script[];
interface Data {
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
interface Inputs {
  [key: string]: any;
}
interface Output {
  id: string;
  description: string;
  website?: string;
  html?: string;
  stylesheets?: Array<string>;
  scripts?: Scripts;
}
interface GoogleAnalyticsOptions {
  id: string;
}
interface GTag {
  (fn: 'js', opt: Date): void;
  (fn: 'config', opt: string): void;
  (
    fn: 'event',
    opt: string,
    opt2?: {
      [key: string]: any;
    },
  ): void;
  (
    fn: 'set',
    opt: {
      [key: string]: string;
    },
  ): void;
  (fn: 'get', opt: string): void;
  (
    fn: 'consent',
    opt: 'default',
    opt2: {
      [key: string]: string;
    },
  ): void;
  (
    fn: 'consent',
    opt: 'update',
    opt2: {
      [key: string]: string;
    },
  ): void;
  (fn: 'config', opt: 'reset'): void;
}
interface GoogleAnalyticsApi {
  dataLayer: Record<string, any>[];
  gtag: GTag;
}
interface GoogleTagManagerOptions {
  id: string;
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
type GoogleTagManager$1 = GoogleTagManagerDataLayerStatus & {
  [key: string]: {
    callback: () => void;
    dataLayer: GoogleTagManagerDataLayerApi;
  };
};
interface GoogleTagManagerApi {
  dataLayer: Record<string, any>[];
  google_tag_manager: GoogleTagManager$1;
}

declare function isExternalScript(script: Script): script is ExternalScript;

declare const GoogleAnalytics: ({ ...args }: Inputs) => Output;

declare const GoogleTagManager: ({ ...args }: Inputs) => Output;

declare const GoogleMapsEmbed: ({ ...args }: Inputs) => Output;

declare const YouTubeEmbed: ({ ...args }: Inputs) => Output;

export {
  type AttributeVal,
  type CodeBlock,
  type Data,
  type ExternalScript,
  type GTag,
  GoogleAnalytics,
  type GoogleAnalyticsApi,
  type GoogleAnalyticsOptions,
  GoogleMapsEmbed,
  GoogleTagManager,
  type GoogleTagManagerApi,
  type GoogleTagManagerOptions,
  type HtmlAttributes,
  type Inputs,
  type Output,
  type Script,
  type Scripts,
  type SrcVal,
  YouTubeEmbed,
  isExternalScript,
};
