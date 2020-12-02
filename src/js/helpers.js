export const clamp = (min, max, value) => value > max ? max : value < min ? min : value;

export const map = (fromStart, fromEnd, toStart, toEnd, value, bClamped) =>
{
  value = bClamped ? clamp(fromStart, fromEnd, value) : value;
  const percentageFrom = (value - fromStart) / (fromEnd - fromStart);
  return percentageFrom * (toEnd - toStart) + toStart;
};
