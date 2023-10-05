import { Script, ExternalScript } from '.';

export function isExternalScript(script: Script): script is ExternalScript {
  return (script as ExternalScript).url !== undefined;
}
