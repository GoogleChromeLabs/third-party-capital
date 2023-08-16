import { formatUrl, createHtml, formatData } from '.';
import type { Data } from '../types';

describe('Utils', () => {
  describe('formatUrl', () => {
    it('should pass user inputs as values for required params', () => {
      const oldUrl = 'https://example.com';
      const requiredParams = ['unit', 'type'];
      const args = {
        unit: 'imperial',
        type: 'main',
      };

      const newUrl = formatUrl(oldUrl, requiredParams, args);
      expect(newUrl).toEqual('https://example.com/?unit=imperial&type=main');
    });
  });

  describe('createHtml', () => {
    it('should construct a HTML element with no attributes or arguments', () => {
      const element = 'lite-element';

      const htmlElement = createHtml(element);
      expect(htmlElement).toEqual('<lite-element></lite-element>');
    });

    it('should construct a HTML element with default attributes and values', () => {
      const element = 'lite-element';
      const defaultAttrs = {
        id: '123',
        loading: 'lazy',
      };

      const htmlElement = createHtml(element, defaultAttrs);
      expect(htmlElement).toEqual(
        '<lite-element id="123" loading="lazy"></lite-element>',
      );
    });

    it('should construct a HTML element passing parameters to any required src URLs', () => {
      const element = 'lite-element';
      const defaultAttrs = {
        id: '123',
        src: {
          url: 'https://example.com/',
          params: ['unit', 'type'],
        },
      };
      const urlQueryParamInputs = {
        unit: 'imperial',
        type: 'main',
      };

      const htmlElement = createHtml(
        element,
        defaultAttrs,
        {},
        urlQueryParamInputs,
      );
      expect(htmlElement).toEqual(
        '<lite-element id="123" src="https://example.com/?unit=imperial&type=main"></lite-element>',
      );
    });

    it('should construct a HTML element overwriting default attribute values with user-defined inputs', () => {
      const element = 'lite-element';
      const defaultAttrs = {
        id: '123',
        src: {
          url: 'https://example.com/',
          params: ['unit', 'type'],
        },
      };
      const htmlAttrInputs = {
        src: 'https://example.com/overwrite',
      };

      const htmlElement = createHtml(element, defaultAttrs, htmlAttrInputs);
      expect(htmlElement).toEqual(
        '<lite-element id="123" src="https://example.com/overwrite"></lite-element>',
      );
    });
  });

  describe('formatData', () => {
    it('should correctly format and overwrite data and inputs', () => {
      const data = {
        id: 'third-party',
        description: 'Description',
        html: {
          element: 'iframe',
          attributes: {
            loading: 'lazy',
            src: {
              url: 'https://www.example.com/',
              params: ['id'],
            },
            width: '100',
            height: '100',
          },
        },
      };

      const inputs = {
        id: '123',
        loading: 'auto',
        width: '150',
      };

      const result = formatData(data, inputs);
      expect(result.html).toEqual(
        '<iframe loading="auto" src="https://www.example.com/?id=123" width="150" height="100"></iframe>',
      );
      expect(result.scripts).toEqual(null);
    });

    it('should pass scripts and correctly assign params if available', () => {
      const data = {
        id: 'third-party',
        description: 'Description',
        html: {
          element: 'iframe',
          attributes: {
            loading: 'lazy',
          },
        },
        scripts: [
          {
            url: 'https://www.example.com',
            params: ['id'],
            strategy: 'worker',
            location: 'head',
            action: 'append',
          },
        ],
      };

      const inputs = {
        id: '123',
      };

      const result = formatData(data as Data, inputs);
      expect(result.html).toEqual('<iframe loading="lazy"></iframe>');
      expect(result.scripts).not.toEqual(null);
      expect(result.scripts!.length).toEqual(1);
      expect(result.scripts![0].url).toEqual('https://www.example.com/?id=123');
    });

    it('should forward all additional inputs as html attributes if not used elsewhere', () => {
      const data = {
        id: 'third-party',
        description: 'Description',
        html: {
          element: 'iframe',
          attributes: {
            loading: 'lazy',
            width: '100',
            height: '100',
          },
        },
      };

      const inputs = {
        id: '123',
        loading: 'auto',
        width: '150',
      };

      const result = formatData(data, inputs);
      expect(result.html).toEqual(
        '<iframe loading="auto" width="150" height="100" id="123"></iframe>',
      );
      expect(result.scripts).toEqual(null);
    });

    it('should include the user inputted slug to the src URL if provided as a parameter', () => {
      const data = {
        id: 'third-party',
        description: 'Description',
        html: {
          element: 'iframe',
          attributes: {
            loading: 'lazy',
            src: {
              url: 'https://www.example.com/',
              slugParam: 'inputSlug',
            },
          },
        },
      };

      const inputs = {
        inputSlug: 'cool-slug',
      };

      const result = formatData(data, inputs);
      expect(result.html).toEqual(
        '<iframe loading="lazy" src="https://www.example.com/cool-slug"></iframe>',
      );
      expect(result.scripts).toEqual(null);
    });

    it('should replace the already existing slug if the user includes a slug parameter slug', () => {
      const data = {
        id: 'third-party',
        description: 'Description',
        html: {
          element: 'iframe',
          attributes: {
            loading: 'lazy',
            src: {
              url: 'https://www.google.com/maps/embed/v1/place',
              slugParam: 'mode',
              params: ['key'],
            },
          },
        },
      };

      const inputs = {
        mode: 'view',
        key: '123',
      };

      const result = formatData(data, inputs);
      expect(result.html).toEqual(
        '<iframe loading="lazy" src="https://www.google.com/maps/embed/v1/view?key=123"></iframe>',
      );
      expect(result.scripts).toEqual(null);
    });
  });
});
