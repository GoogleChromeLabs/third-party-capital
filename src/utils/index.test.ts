import { formatUrl, createHtml } from '.';

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
      const attrs = {
        id: '123',
        loading: 'lazy',
      };

      const htmlElement = createHtml(element, attrs);
      expect(htmlElement).toEqual(
        '<lite-element id="123" loading="lazy"></lite-element>',
      );
    });

    it('should construct a HTML element passing parameters to any required src URLs', () => {
      const element = 'lite-element';
      const attrs = {
        id: '123',
        src: {
          url: 'https://example.com/',
          params: ['unit', 'type'],
        },
      };
      const args = {
        unit: 'imperial',
        type: 'main',
      };

      const htmlElement = createHtml(element, attrs, args);
      expect(htmlElement).toEqual(
        '<lite-element id="123" src="https://example.com/?unit=imperial&type=main"></lite-element>',
      );
    });

    it('should construct a HTML element overwriting default attribute values with user-defined inputs', () => {
      const element = 'lite-element';
      const attrs = {
        id: '123',
        src: {
          url: 'https://example.com/',
          params: ['unit', 'type'],
        },
      };
      const args = {
        src: 'https://example.com/overwrite',
      };

      const htmlElement = createHtml(element, attrs, args);
      expect(htmlElement).toEqual(
        '<lite-element id="123" src="https://example.com/overwrite"></lite-element>',
      );
    });
  });
});
