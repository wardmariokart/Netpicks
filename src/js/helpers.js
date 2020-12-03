export const clamp = (min, max, value) => value > max ? max : value < min ? min : value;

export const map = (fromStart, fromEnd, toStart, toEnd, value, bClamped) =>
{
  value = bClamped ? clamp(fromStart, fromEnd, value) : value;
  const percentageFrom = (value - fromStart) / (fromEnd - fromStart);
  return percentageFrom * (toEnd - toStart) + toStart;
};

export const formDataToJson = $form =>
{
  const data = new FormData($form);
  const obj = {};
  data.forEach((value, key) =>
  {
    obj[key] = value;
  });
  return obj;
};

export const postToPHP = async (formData, url) =>
{
  const fetchResult = await fetch(url, {
    method: 'POST',
    headers: new Headers({
      'Content-Type': 'application/json'
    }),
    body: JSON.stringify(formData)
  });

  const jsonResult = await fetchResult.json();
  return jsonResult;
};
