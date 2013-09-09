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
 */

package com.adwhirl.obj;

public class Ration implements Comparable<Ration> {
  public String nid = "";
  public int type = 0;
  public String name = "";
  public double weight = 0;
  public String key = "";
  public String key2 = "";
  public int priority = 0;

  public Ration() {
  }

  public int compareTo(Ration another) {
    int otherPriority = another.priority;
    if (this.priority < otherPriority) {
      return -1;
    } else if (this.priority > otherPriority) {
      return 1;
    } else {
      return 0;
    }
  }
}
