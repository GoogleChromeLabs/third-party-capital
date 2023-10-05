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
  strategy: ScriptStrategy;
  location: ScriptLocation;
  action: ScriptAction;
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

export function isExternalScript(script: Script): script is ExternalScript {
  return (script as ExternalScript).url !== undefined;
}
