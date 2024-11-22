// import { computed } from 'vue';

export const getAvailableStartingDate = () => {
  const date = new Date();
  return date;
};

export const getActiveView = () => {
  return 'month'
};

export const getSpecialHours = (dailyHours) => {
  return {
    1: dailyHours,
    2: dailyHours,
    3: dailyHours,
    4: dailyHours,
    5: dailyHours,
    6: dailyHours,
    7: dailyHours,
  };
};

export const getHiddenWeekDays = (tutorWorkDays) => {
  if (!tutorWorkDays) {
    return [];
  }

  const dayToNumber = {
    monday: 1,
    tuesday: 2,
    wednesday: 3,
    thursday: 4,
    friday: 5,
    saturday: 6,
    sunday: 7,
  };

  return Object.keys(tutorWorkDays)
    .filter(day => !tutorWorkDays[day])
    .map(day => dayToNumber[day]);
};
