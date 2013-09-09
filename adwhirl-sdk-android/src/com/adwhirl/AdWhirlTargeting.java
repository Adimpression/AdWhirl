/*
 Copyright 2009-2010 AdMob, Inc.
 
    Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at
 
  http://www.apache.org/licenses/LICENSE-2.0
 
 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
 
 Based on contributions by Joe Hansche <jhansche@myyearbook.com>
 */

package com.adwhirl;

import java.util.Calendar;
import java.util.GregorianCalendar;
import java.util.HashSet;
import java.util.Set;

public class AdWhirlTargeting {
  private static boolean testMode;
  private static Gender gender;
  private static GregorianCalendar birthDate;
  private static String postalCode;
  private static String keywords;
  private static Set<String> keywordSet;

  static {
    resetData();
  }

  public static void resetData() {
    AdWhirlTargeting.testMode = false;
    AdWhirlTargeting.gender = Gender.UNKNOWN;
    AdWhirlTargeting.birthDate = null;
    AdWhirlTargeting.postalCode = null;
    AdWhirlTargeting.keywords = null;
    AdWhirlTargeting.keywordSet = null;
  }

  public static boolean getTestMode() {
    return testMode;
  }

  public static void setTestMode(boolean testMode) {
    AdWhirlTargeting.testMode = testMode;
  }

  public static enum Gender {
    UNKNOWN, MALE, FEMALE
  }

  public static Gender getGender() {
    return gender;
  }

  public static void setGender(Gender gender) {
    if (gender == null) {
      gender = Gender.UNKNOWN;
    }

    AdWhirlTargeting.gender = gender;
  }

  public static int getAge() {
    if (birthDate != null) {
      return Calendar.getInstance().get(Calendar.YEAR)
          - birthDate.get(Calendar.YEAR);
    }

    return -1;
  }

  public static GregorianCalendar getBirthDate() {
    return birthDate;
  }

  public static void setBirthDate(GregorianCalendar birthDate) {
    AdWhirlTargeting.birthDate = birthDate;
  }

  public static void setAge(int age) {
    AdWhirlTargeting.birthDate = new GregorianCalendar(Calendar.getInstance()
        .get(Calendar.YEAR)
        - age, 0, 1);
  }

  public static String getPostalCode() {
    return postalCode;
  }

  public static void setPostalCode(String postalCode) {
    AdWhirlTargeting.postalCode = postalCode;
  }

  public static Set<String> getKeywordSet() {
    return keywordSet;
  }

  public static String getKeywords() {
    return keywords;
  }

  public static void setKeywordSet(Set<String> keywords) {
    AdWhirlTargeting.keywordSet = keywords;
  }

  public static void setKeywords(String keywords) {
    AdWhirlTargeting.keywords = keywords;
  }

  public static void addKeyword(String keyword) {
    if (keywordSet == null) {
      AdWhirlTargeting.keywordSet = new HashSet<String>();
    }
    keywordSet.add(keyword);
  }
}
